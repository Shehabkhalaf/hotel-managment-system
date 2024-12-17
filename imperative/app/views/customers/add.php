<?php
//require 'app/views/header.php';
//?>

<h2>Add New Customer</h2>
<form action="store" method="POST">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <br>
    <div class="form-group">
        <label for="contact_info">Email</label>
        <input type="text" id="contact_info" name="email" class="form-control" required>
    </div>
    <br>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Add Customer</button>
</form>

<?php
//require 'app/views/footer.php';
//?>
