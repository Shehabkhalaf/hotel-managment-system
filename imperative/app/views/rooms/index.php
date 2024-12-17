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
    <?php while($room = $rooms->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $room['id']; ?></td>
            <td><?php echo $room['type']; ?></td>
            <td><?php echo $room['price']; ?></td>
            <td><?php echo $room['availability'] ? 'available' : 'booked'; ?></td>
            <td>
                <form action="bookRoom" method="post">
                    <input hidden name="room_id" value="<?php echo $room['id']; ?>">
                    <label for="customer_id">Choose Customer:</label>
                    <select id="customer_id" name="customer_id">
                        <?php
                        $customers->data_seek(0);
                        while($customer = $customers->fetch_assoc()){ ?>
                            <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                        <?php } ?>
                    </select>
                    <label for="timestamp">Booking Date & Time:</label>
                    <input type="datetime-local" id="timestamp" name="leave_time" required>
                    <button type="submit">Book room</button>
                </form>
                <form action="releaseRoom" method="post">
                    <input hidden name="room_id" value=<?php echo $room['id']?>>
                    <button type="submit">Release room</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<?php //require 'templates/footer.php'; ?>
