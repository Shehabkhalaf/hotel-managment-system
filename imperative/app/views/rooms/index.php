<?php //require '../templates/header.php'; ?>

<h1>Rooms</h1>

<a href="create">Add New Room</a>

<table>
    <tr>
        <th>ID</th>
        <th>Room Type</th>
        <th>Price</th>
        <th>Availability</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($rooms as $room): ?>
    <tr>
        <td><?php echo $room['id']; ?></td>
        <td><?php echo $room['type']; ?></td>
        <td><?php echo $room['price']; ?></td>
        <td><?php echo $room['availability'] ? 'available' : 'booked'; ?></td>
        <td>
<!--            <a href="index.php?action=edit_room&id=--><?php //echo $room['id']; ?><!--">Edit</a>-->
<!--            <a href="index.php?action=delete_room&id=--><?php //echo $room['id']; ?><!--">Delete</a>-->
            <a href="index.php?action=check_availability&id=<?php echo $room['id']; ?>">Check Availability</a>
            <a href="index.php?action=book_room&id=<?php echo $room['id']; ?>">Book Room</a>
            <a href="index.php?action=release_room&id=<?php echo $room['id']; ?>">Release Room</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php //require 'templates/footer.php'; ?>
