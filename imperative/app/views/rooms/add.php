<?php //require 'templates/header.php'; ?>

<h1>Add New Room</h1>

<form method="POST" action="store">
    <label for="room_type">Room Type:</label>
    <input type="text" id="room_type" name="type" required>
    <br>
    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required>
    <br>
    <label for="availability">Availability:</label>
    <select id="availability" name="availability" required>
        <option value="available">Available</option>
        <option value="booked">Booked</option>
    </select>
    <br>
    <input type="submit" value="Add Room">
</form>

<?php //require 'templates/footer.php'; ?>
