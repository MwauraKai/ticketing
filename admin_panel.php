<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
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

        form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
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

        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }

        .btn-remove {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-edit, .btn-remove {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Hi Admin, you have the full privileges to add/remove/edit and view booked events on this page</h3>
        <br>
        <!-- Add the button -->
        <!-- Create a container for the content -->
        <div id="adminReservationsContainer">
            <button onclick="loadAdminReservations()">ALL RESERVATIONS</button>
        </div>
        <br>

        <!-- JavaScript to dynamically load content -->
        <script>
            function loadAdminReservations() {
                var container = document.getElementById("adminReservationsContainer");
                container.innerHTML = "";

                // Load Admin Reservations dynamically
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        container.innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "admin_reservations.php", true);
                xhttp.send();
            }
        </script>

        <!-- Add Event Form -->
        <form action="admin_panel.php" method="post">
            <h3>Add New Event</h3>
            <div class="form-group">
                <label for="eventName">Event Name:</label>
                <input type="text" class="form-control" id="eventName" name="eventName" required>
            </div>
            <div class="form-group">
                <label for="ticketPriceVIP">Ticket Price (VIP):</label>
                <input type="number" class="form-control" id="ticketPriceVIP" name="ticketPriceVIP" required>
            </div>
            <div class="form-group">
                <label for="ticketPriceRegular">Ticket Price (Regular):</label>
                <input type="number" class="form-control" id="ticketPriceRegular" name="ticketPriceRegular" required>
            </div>
            <div class="form-group">
                <label for="maxAttendees">Max Attendees:</label>
                <input type="number" class="form-control" id="maxAttendees" name="maxAttendees" required>
            </div>
            <div class="form-group">
                <label for="eventDate">Event Date:</label>
                <input type="date" class="form-control" id="eventDate" name="eventDate" required>
            </div>
            <div class="form-group">
                <label for="eventTime">Event Time:</label>
                <input type="time" class="form-control" id="eventTime" name="eventTime" required>
            </div>
            <div class="form-group">
                <label for="eventLocation">Event Location:</label>
                <input type="text" class="form-control" id="eventLocation" name="eventLocation" required>
            </div>
            <button type="submit" class="btn btn-success" name="addEvent">Add Event</button>
        </form>
        <br>

        <!-- Event Listing -->
        <h3>Event Listing</h3>
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
                        <th>Event Time</th>
                        <th>Event Location</th>
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
                            <td>
                                <!-- Edit Event Button -->
                                <a href="edit_events.php?eventId=<?= $event['event_id']; ?>" class="btn btn-warning btn-edit btn-sm">Edit</a>
                                <!-- Remove Event Button -->
                                <a href="admin_panel.php?removeEvent=<?= $event['event_id']; ?>" class="btn btn-danger btn-remove btn-sm" onclick="return confirm('Are you sure you want to remove this event?')">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No events available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
