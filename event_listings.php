<!-- user_reservations.php -->

<?php
// Include your database connection file here
include 'db_conn.php';
include 'user_actions.php'; // Include the user actions file

// Example: Start the session (You should start the session in your login mechanism)
// session_start();

// Check if user is logged in and get user ID from session
if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
} else {
    // Redirect to login page if not logged in
    header("Location: login.php"); // Replace with your login page URL
    exit();
}

// Fetch user reservations
$sqlReservations = "SELECT b.booking_id, e.event_name, e.event_location, e.event_date, e.event_time, b.ticket_type
                    FROM bookings b
                    INNER JOIN events e ON b.event_id = e.event_id
                    WHERE b.user_id=$userId";

$resultReservations = mysqli_query($conn, $sqlReservations);

if ($resultReservations) {
    $reservations = mysqli_fetch_all($resultReservations, MYSQLI_ASSOC);
} else {
    $reservations = array(); // Set $reservations as an empty array if there's an issue with the query
}
?>

<h3>Your Reservations</h3>
<?php if (!empty($reservations)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Event Name</th>
                <th>Event Location</th>
                <th>Event Date</th>
                <th>Event Time</th>
                <th>Ticket Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation) : ?>
                <tr>
                    <td><?= $reservation['booking_id']; ?></td>
                    <td><?= $reservation['event_name']; ?></td>
                    <td><?= $reservation['event_location']; ?></td>
                    <td><?= $reservation['event_date']; ?></td>
                    <td><?= $reservation['event_time']; ?></td>
                    <td><?= $reservation['ticket_type']; ?></td>
                    <td>
                        <!-- Cancel Reservation Button -->
                        <a href="user_actions.php?action=cancel&bookingId=<?= $reservation['booking_id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>No reservations yet.</p>
<?php endif; ?>
