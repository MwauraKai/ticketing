<?php
// admin_actions.php

// Include your database connection file here
include 'db_conn.php';

// Function to sanitize input data
function sanitize($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

// Add Event
if (isset($_POST['addEvent'])) {
    $eventName = sanitize($_POST['eventName']);
    $ticketPriceVIP = sanitize($_POST['ticketPriceVIP']);
    $ticketPriceRegular = sanitize($_POST['ticketPriceRegular']);
    $maxAttendees = sanitize($_POST['maxAttendees']);
    $eventDate = sanitize($_POST['eventDate']);
    $eventTime = sanitize($_POST['eventTime']);
    $eventLocation = sanitize($_POST['eventLocation']);

    $sql = "INSERT INTO events (event_name, ticket_price_vip, ticket_price_regular, max_attendees, event_date, event_time, event_location) 
            VALUES ('$eventName', $ticketPriceVIP, $ticketPriceRegular, $maxAttendees, '$eventDate', '$eventTime', '$eventLocation')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: admin_panel.php?success=Event added successfully");
        exit();
    } else {
        header("Location: admin_panel.php?error=Error adding event");
        exit();
    }
}

// Edit Event
if (isset($_POST['editEvent'])) {
    $eventId = sanitize($_POST['eventId']);
    $eventName = sanitize($_POST['eventName']);
    $ticketPriceVIP = sanitize($_POST['ticketPriceVIP']);
    $ticketPriceRegular = sanitize($_POST['ticketPriceRegular']);
    $maxAttendees = sanitize($_POST['maxAttendees']);
    $eventDate = sanitize($_POST['eventDate']);
    $eventTime = sanitize($_POST['eventTime']);
    $eventLocation = sanitize($_POST['eventLocation']);

    $sql = "UPDATE events SET 
            event_name='$eventName', 
            ticket_price_vip=$ticketPriceVIP, 
            ticket_price_regular=$ticketPriceRegular, 
            max_attendees=$maxAttendees, 
            event_date='$eventDate',
            event_time='$eventTime',
            event_location='$eventLocation'
            WHERE event_id=$eventId";

    if (mysqli_query($conn, $sql)) {
        header("Location: admin_panel.php?success=Event updated successfully");
        exit();
    } else {
        header("Location: admin_panel.php?error=Error updating event");
        exit();
    }
}

// Remove Event
if (isset($_GET['removeEvent'])) {
    $eventId = sanitize($_GET['removeEvent']);

    $sql = "DELETE FROM events WHERE event_id=$eventId";

    if (mysqli_query($conn, $sql)) {
        header("Location: admin_panel.php?success=Event removed successfully");
        exit();
    } else {
        header("Location: admin_panel.php?error=Error removing event");
        exit();
    }
}

