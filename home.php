<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>HOME</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
     <h1 class="welcome-header">Welcome, <?php echo $_SESSION['user_name']; ?>! <a href="logout.php">Logout</a></h1>
        <h2 class="system-title">Welcome To Our Exciting Ticket Reservation System</h2>
        <p class="system-description">Experience the ease and joy of reserving tickets for upcoming events. With our system, you can:</p>
        <ul class="features-list">
            <li>Explore a variety of upcoming events.</li>
            <li>Reserve both Regular and VIP tickets.</li>
            <li>Check the remaining available tickets for each event.</li>
            <li>Quickly view your reservations and upcoming events in one place.</li>
        </ul>
        <p class="cta-text">Don't miss out on the fun! Click below to start exploring and reserving your tickets:</p>
        <h1><button class="btn btn-primary" onclick="viewEventsAndReservations()">VIEW EVENTS AND RESERVATIONS</button></h1>

      <script>
            function viewEventsAndReservations() {
                window.location.href = 'user_interface.php';
            }
      </script>
</body>
</html>

<?php 
}else{
     header("Location: index.php");
     exit();
}
 ?>