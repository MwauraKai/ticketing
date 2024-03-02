<?php
// user_actions.php

// Start the session to access user information
session_start();

// Include your database connection file here
include 'db_conn.php';

// Function to sanitize input data
function sanitize($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Reserve Regular Ticket
if (isset($_GET['action']) && $_GET['action'] == 'reserveRegular') {
    reserveTicket('Regular');
}

// Reserve VIP Ticket
if (isset($_GET['action']) && $_GET['action'] == 'reserveVIP') {
    reserveTicket('VIP');
}

// Cancel Reservation
if (isset($_GET['action']) && $_GET['action'] == 'cancel') {
    $userId = $_SESSION['id']; // Get user ID from the session
    $bookingId = sanitize($_GET['bookingId']);

    // Delete the reservation from the bookings table
    $sql = "DELETE FROM bookings WHERE user_id = ? AND booking_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $bookingId);
        mysqli_stmt_execute($stmt);

        header("Location: user_interface.php?success=Reservation canceled successfully");
        exit();
    } else {
        header("Location: user_interface.php?error=Error canceling reservation");
        exit();
    }
}

function reserveTicket($ticketType)
{
    $userId = $_GET['userId'];
    $eventId = sanitize($_GET['eventId']);

    // Check if the user has already reserved the maximum allowed tickets (5)
    $sqlCheckReservations = "SELECT COUNT(*) AS numReservations FROM bookings WHERE user_id = ?";
    $stmtCheckReservations = mysqli_prepare($GLOBALS['conn'], $sqlCheckReservations);

    if ($stmtCheckReservations) {
        mysqli_stmt_bind_param($stmtCheckReservations, 'i', $userId);
        mysqli_stmt_execute($stmtCheckReservations);
        mysqli_stmt_store_result($stmtCheckReservations);

        if (mysqli_stmt_num_rows($stmtCheckReservations) > 0) {
            mysqli_stmt_bind_result($stmtCheckReservations, $numReservations);
            mysqli_stmt_fetch($stmtCheckReservations);

            if ($numReservations >= 5) {
                header("Location: user_interface.php?error=You have reached the maximum allowed reservations (5)");
                exit();
            }
        }
    } else {
        header("Location: user_interface.php?error=Error checking reservations");
        exit();
    }

    // Check if the user has already reserved a ticket for this event
    $sqlCheckReservation = "SELECT * FROM bookings WHERE user_id = ? AND event_id = ?";
    $stmtCheckReservation = mysqli_prepare($GLOBALS['conn'], $sqlCheckReservation);

    if ($stmtCheckReservation) {
        mysqli_stmt_bind_param($stmtCheckReservation, 'ii', $userId, $eventId);
        mysqli_stmt_execute($stmtCheckReservation);
        mysqli_stmt_store_result($stmtCheckReservation);

        //if (mysqli_stmt_num_rows($stmtCheckReservation) > 0) {
          //  header("Location: user_interface.php?error=You have already reserved a ticket for this event");
          //  exit();
       // }
       
    } else {
        header("Location: user_interface.php?error=Error checking existing reservations");
        exit();
    }

    // Check if the event has reached the maximum number of attendees
    $sqlCheckAttendees = "SELECT COUNT(*) AS numAttendees FROM bookings WHERE event_id = ?";
    $stmtCheckAttendees = mysqli_prepare($GLOBALS['conn'], $sqlCheckAttendees);

    if ($stmtCheckAttendees) {
        mysqli_stmt_bind_param($stmtCheckAttendees, 'i', $eventId);
        mysqli_stmt_execute($stmtCheckAttendees);
        mysqli_stmt_store_result($stmtCheckAttendees);

        if (mysqli_stmt_num_rows($stmtCheckAttendees) > 0) {
            mysqli_stmt_bind_result($stmtCheckAttendees, $numAttendees);
            mysqli_stmt_fetch($stmtCheckAttendees);

            // Fetch the maximum allowed attendees for the event
            $sqlMaxAttendees = "SELECT max_attendees FROM events WHERE event_id = ?";
            $stmtMaxAttendees = mysqli_prepare($GLOBALS['conn'], $sqlMaxAttendees);

            if ($stmtMaxAttendees) {
                mysqli_stmt_bind_param($stmtMaxAttendees, 'i', $eventId);
                mysqli_stmt_execute($stmtMaxAttendees);
                mysqli_stmt_store_result($stmtMaxAttendees);

                if (mysqli_stmt_num_rows($stmtMaxAttendees) > 0) {
                    mysqli_stmt_bind_result($stmtMaxAttendees, $maxAttendees);
                    mysqli_stmt_fetch($stmtMaxAttendees);

                    if ($numAttendees >= $maxAttendees) {
                        header("Location: user_interface.php?error=Event has reached the maximum number of attendees");
                        exit();
                    }
                }
            } else {
                header("Location: user_interface.php?error=Error fetching event information");
                exit();
            }
        }
    } else {
        header("Location: user_interface.php?error=Error checking event attendees");
        exit();
    }

    // Insert the reservation into the bookings table
    $sqlReserveTicket = "INSERT INTO bookings (user_id, event_id, ticket_type) VALUES (?, ?, ?)";
    $stmtReserveTicket = mysqli_prepare($GLOBALS['conn'], $sqlReserveTicket);

    if ($stmtReserveTicket) {
        mysqli_stmt_bind_param($stmtReserveTicket, 'iis', $userId, $eventId, $ticketType);
        mysqli_stmt_execute($stmtReserveTicket);

        header("Location: user_interface.php?success=Reservation successful");
        exit();
    } else {
        header("Location: user_interface.php?error=Error reserving ticket");
        exit();
    }
}
