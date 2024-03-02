<?php
// admin_reservations.php

// Include your database connection file here
include 'db_conn.php';
include 'admin_actions.php'; // Include the admin actions file

// Fetch reservations and total amount generated
$sqlReservations = "SELECT u.first_name, u.last_name, u.email, e.event_name, b.ticket_type,
                           e.event_date, e.event_location, e.ticket_price_vip, e.ticket_price_regular, e.event_time
                    FROM bookings b
                    INNER JOIN events e ON b.event_id = e.event_id
                    INNER JOIN users u ON b.user_id = u.id";

$resultReservations = mysqli_query($conn, $sqlReservations);

if ($resultReservations) {
    $reservations = mysqli_fetch_all($resultReservations, MYSQLI_ASSOC);
} else {
    $reservations = array(); // Set $reservations as an empty array if there's an issue with the query
}

// Calculate total amount generated
$totalAmount = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reservations</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Reservations</h1>
        <br>
        <!-- Reservations Listing -->
        <?php if (!empty($reservations)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Event Name</th>
                        <th>Ticket Type</th>
                        <th>Event Date</th>
                        <th>Event Location</th>
                        <th>Ticket Price</th>
                        <th>Event Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation) : ?>
                        <tr>
                            <td><?= $reservation['first_name']; ?></td>
                            <td><?= $reservation['last_name']; ?></td>
                            <td><?= $reservation['email']; ?></td>
                            <td><?= $reservation['event_name']; ?></td>
                            <td><?= $reservation['ticket_type']; ?></td>
                            <td><?= $reservation['event_date']; ?></td>
                            <td><?= $reservation['event_location']; ?></td>
                            <?php if ($reservation['ticket_type'] === 'VIP') : ?>
                                <td><?= $reservation['ticket_price_vip']; ?></td>
                                <?php $totalAmount += $reservation['ticket_price_vip']; ?>
                            <?php endif; ?>
                            <?php if ($reservation['ticket_type'] === 'Regular') : ?>
                                <td><?= $reservation['ticket_price_regular']; ?></td>
                                <?php $totalAmount += $reservation['ticket_price_regular']; ?>
                            <?php endif; ?>
                            <td><?= $reservation['event_time']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Display Total Amount Generated -->
            <p>Total Amount Generated: $<?= $totalAmount; ?></p>

        <?php else : ?>
            <p>No reservations yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
