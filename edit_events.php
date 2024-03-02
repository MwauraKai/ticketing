<?php
// edit_event.php

// Include your database connection file here
include 'db_conn.php';
include 'admin_actions.php'; // Include the admin actions file

if (isset($_GET['eventId'])) {
    $eventId = $_GET['eventId'];

    // Fetch event details by ID
    $sql = "SELECT * FROM events WHERE event_id=$eventId";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $event = mysqli_fetch_assoc($result);
    } else {
        header("Location: admin_panel.php?error=Event not found");
        exit();
    }
} else {
    header("Location: admin_panel.php?error=Invalid request");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Event</h2>

        <!-- Edit Event Form -->
        <form action="admin_actions.php" method="post">
            <!-- Hidden input field to store the event ID -->
            <input type="hidden" name="eventId" value="<?= $event['event_id']; ?>">
            
            <!-- Editable Form fields for editing event -->
<div class="form-group">
    <label for="eventName">Event Name:</label>
    <input type="text" class="form-control" id="eventName" name="eventName" value="<?= $event['event_name']; ?>">
</div>

            <div class="form-group">
                <label for="ticketPriceVIP">Ticket Price VIP:</label>
                <input type="number" class="form-control" id="ticketPriceVIP" name="ticketPriceVIP" value="<?= $event['ticket_price_vip']; ?>">
            </div>

            <div class="form-group">
                <label for="ticketPriceRegular">Ticket Price Regular:</label>
                <input type="number" class="form-control" id="ticketPriceRegular" name="ticketPriceRegular" value="<?= $event['ticket_price_regular']; ?>">
            </div>

            <div class="form-group">
                <label for="maxAttendees">Max Attendees:</label>
                <input type="number" class="form-control" id="maxAttendees" name="maxAttendees" value="<?= $event['max_attendees']; ?>">
            </div>

            <div class="form-group">
                <label for="eventDate">Event Date:</label>
                <input type="date" class="form-control" id="eventDate" name="eventDate" value="<?= $event['event_date']; ?>">
            </div>

            <div class="form-group">
                <label for="eventTime">Event Time:</label>
                <input type="time" class="form-control" id="eventTime" name="eventTime" value="<?= $event['event_time']; ?>">
            </div>

            <div class="form-group">
                <label for="eventLocation">Event Location:</label>
                <input type="text" class="form-control" id="eventLocation" name="eventLocation" value="<?= $event['event_location']; ?>">
            </div>
            
            <button type="submit" class="btn btn-primary" name="editEvent">Save Changes</button>
        </form>
    </div>
</body>
</html>
