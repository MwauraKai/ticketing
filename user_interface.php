<?php
// user_interface.php

// Include your database connection file here
include 'db_conn.php';
include 'user_actions.php'; // Include the user actions file

// Example: Start the session (You should start the session in your login mechanism)
// session_start();

// Check if user is logged in and get user ID from session
if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    $userId = $_SESSION['id'];
} else {
    // Redirect to login page if not logged in
    header("Location: login.php"); // Replace with your login page URL
    exit();
}

// Fetch upcoming events
$sql = "SELECT e.event_id, e.event_name, e.ticket_price_vip, e.ticket_price_regular,
               e.max_attendees, e.event_date, e.event_time, e.event_location,
        COUNT(b.booking_id) AS numAttendees,
        (e.max_attendees - COUNT(b.booking_id)) AS ticketsLeft
        FROM events e
        LEFT JOIN bookings b ON e.event_id = b.event_id
        GROUP BY e.event_id
        ORDER BY e.event_date";

$result = mysqli_query($conn, $sql);

if ($result) {
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $events = array(); // Set $events as an empty array if there's an issue with the query
}

// Fetch user reservations
$sqlReservations = "SELECT b.*, e.event_name, e.event_location, e.event_date, e.event_time
                    FROM bookings b
                    INNER JOIN events e ON b.event_id = e.event_id
                    WHERE b.user_id = $userId";

$resultReservations = mysqli_query($conn, $sqlReservations);

if ($resultReservations) {
    $reservations = mysqli_fetch_all($resultReservations, MYSQLI_ASSOC);
} else {
    $reservations = array(); // Set $reservations as an empty array if there's an issue with the query
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Interface</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .container {
        margin-top: 50px;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
    }

    h2 {
        margin: 0;
    }

    .logout-link {
        text-decoration: none;
        color: red;
        margin-left: auto;
    }

    h1, h3 {
        color: #007bff;
    }

    button {
        background-color: #28a745;
        color: #fff;
        padding: 10px;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #218838;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #007bff;
        color: #fff;
    }

    tbody tr:hover {
        background-color: #f5f5f5;
    }

    .btn-success {
        background-color: #28a745;
        color: #fff;
        margin-right: 5px;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        margin-right: 5px;
    }

    .btn-success:disabled {
        background-color: #6c757d;
        color: #fff;
    }
</style>

</head>
<body>
    <div class="container mt-5">
        <div class="header-container">
            <h2><?php echo $_SESSION['name']; ?>
              <a href="logout.php" class="logout-link">Logout</a>
            </h2>
        </div>
        <br>

        <h3>Hi, This page allows you can view your reservations, make new ones or cancel existing one</h3>
        <br>
        <div id="eventListingContainer">
            <button onclick="loadEventListing()">VIEW YOUR RESERVATIONS</button>
        </div> <br>

        <script>
            function loadEventListing() {
                var container = document.getElementById("eventListingContainer");
                container.innerHTML = "";

                // Load Event Listing dynamically
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        container.innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "event_listings.php", true);
                xhttp.send();
            }
        </script>
        <!-- Upcoming Events -->
        <h3>Upcoming Events</h3>
        <?php if (!empty($events)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event Name</th>
                        <th>Ticket Price (VIP)</th>
                        <th>Ticket Price (Regular)</th>
                        <th>Max Attendees</th>
                        <th>Event Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Tickets Left</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event) : ?>
                        <tr>
                            <td><?= $event['event_id']; ?></td>
                            <td><?= $event['event_name']; ?></td>
                            <td><?= $event['ticket_price_vip']; ?></td>
                            <td><?= $event['ticket_price_regular']; ?></td>
                            <td><?= $event['max_attendees']; ?></td>
                            <td><?= $event['event_date']; ?></td>
                            <td><?= $event['event_time']; ?></td>
                            <td><?= $event['event_location']; ?></td>
                            <td><?= $event['ticketsLeft']; ?></td>
                            <td>
                                <!-- Reserve Ticket Button -->
                                <?php if ($event['numAttendees'] < $event['max_attendees']) : ?>
                                    <!-- Reserve Regular Ticket Button -->
                                    <a href="user_actions.php?action=reserveRegular&eventId=<?= $event['event_id']; ?>&userId=<?= $userId; ?>" class="btn btn-success btn-sm">
                                    Reserve Regular
                                    </a>
                                 
                                    <!-- Reserve VIP Ticket Button -->
                                    <a href="user_actions.php?action=reserveVIP&eventId=<?= $event['event_id']; ?>&userId=<?= $userId; ?>" class="btn btn-success btn-sm">
                                    Reserve VIP
                                    </a>
                                <?php else : ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Event Full</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No upcoming events.</p>
        <?php endif; ?>
        <br>
    </div>
</body>
</html>
