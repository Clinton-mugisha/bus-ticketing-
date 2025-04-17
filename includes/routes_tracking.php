<section id="routes" class="popular-routes">
    <h2>Popular Routes</h2>
    <div class="routes-container">
        <?php foreach ($popular_routes as $route): ?>
        <div class="route-card">
            <div class="route-info">
                <h3><?= $route['from'] ?> to <?= $route['to'] ?></h3>
                <p class="route-details">
                    <i class="fas fa-<?= $route['type'] === 'bus' ? 'bus' : 'train' ?>"></i> 
                    <?= ucfirst($route['type']) ?> Service
                </p>
                <p class="route-price">From UGX <?= number_format($route['price']) ?></p>
                <p class="route-time"><i class="fas fa-clock"></i> <?= $route['time'] ?></p>
            </div>
            <form method="post" action="#book">
                <input type="hidden" name="departure" value="<?= strtolower($route['from']) ?>">
                <input type="hidden" name="destination" value="<?= strtolower($route['to']) ?>">
                <input type="hidden" name="travel_type" value="<?= $route['type'] ?>">
                <button type="submit" class="book-route-btn">Book Now</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="track" class="tracking-section">
    <h2>Track Your Journey</h2>
    <div class="tracking-container">
        <div class="tracking-form">
            <form method="post" action="#track">
                <div class="form-group">
                    <label for="booking-id">Booking ID</label>
                    <input type="text" id="booking-id" name="booking_id" placeholder="Enter your booking ID" value="<?= isset($_POST['booking_id']) ? htmlspecialchars($_POST['booking_id']) : '' ?>">
                </div>
                <button type="submit" name="track_journey" id="track-btn" class="track-btn">Track Now</button>
            </form>
            <?php if (isset($tracking_error)): ?>
                <div class="error-message">
                    <p><?= $tracking_error ?></p>
                </div>
            <?php endif; ?>
        </div>
        <div id="tracking-result" class="tracking-result">
            <?php if ($tracking_info): ?>
                <div class="tracking-map">
                    <div class="map-header">
                        <h3>Journey in Progress</h3>
                        <div class="journey-info">
                            <div class="from-to">
                                <span><?= $tracking_info['from'] ?></span>
                                <i class="fas fa-arrow-right"></i>
                                <span><?= $tracking_info['to'] ?></span>
                            </div>
                            <div class="eta">
                                <i class="fas fa-clock"></i>
                                <span>ETA: <?= $tracking_info['eta'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="map-container">
                        <div class="google-map">
                            <img src="https://maps.googleapis.com/maps/api/staticmap?center=0.347596,32.582520&zoom=10&size=600x300&maptype=roadmap&markers=color:red%7Clabel:A%7C0.347596,32.582520&markers=color:blue%7Clabel:B%7C0.0611,32.4432&key=AIzaSyB71w-3jS1Nm1ymJN4T8VMFOE798prR9-I" alt="Map">
                        </div>
                    </div>
                    <div class="journey-progress">
                        <div class="progress-bar">
                            <div class="progress" style="width: <?= $tracking_info['progress'] ?>%"></div>
                        </div>
                        <div class="progress-info">
                            <span><?= $tracking_info['progress'] ?>% completed</span>
                            <span><?= $tracking_info['remaining'] ?> remaining</span>
                        </div>
                    </div>
                    <div class="journey-updates">
                        <h4>Updates</h4>
                        <?php foreach ($tracking_info['updates'] as $update): ?>
                            <div class="update-item">
                                <div class="update-time"><?= $update['time'] ?></div>
                                <div class="update-info"><?= $update['info'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="map-placeholder">
                    <i class="fas fa-map-marked-alt"></i>
                    <p>Enter your booking ID to see live tracking</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>