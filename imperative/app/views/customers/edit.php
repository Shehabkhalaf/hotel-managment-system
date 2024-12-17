<?php
//require 'app/views/header.php';]

//?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Customer</title>
</head>
<body>
    <h2>Edit Customer</h2>
    <form action="http://localhost/hotel-managment-system/imperative/customer/update" method="POST">
        <input hidden type="text" id="name" name="id" class="form-control" value="<?php echo $customer[0]['id']; ?>" required>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo $customer[0]['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" class="form-control" value="<?php echo $customer[0]['email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" class="form-control" value="<?php echo $customer[0]['phone']; ?>" required>
        </div>
        <button type="submit" class="btn btn-warning">Update Customer</button>
    </form>
</body>
</html>

<?php
//require 'app/views/footer.php';
//?>
