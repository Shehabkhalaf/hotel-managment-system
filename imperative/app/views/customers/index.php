<?php
// require 'app/views/header.php';

echo "<h2>Customers</h2>";
echo "<a href='create' class='btn btn-primary'>Add New Customer</a>";
echo "<br><br>";

// Search Form with Input Field
echo "<form action='search' method='GET' class='form-inline' style='margin-bottom: 20px;'>
        <input type='text' name='name' placeholder='Enter customer name' class='form-control' required>
        <button type='submit' class='btn btn-secondary' style='margin-left: 10px;'>Search</button>
      </form>";

echo "<table class='table'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>";

foreach ($customers as $customer) {
    echo "<tr>
            <td>{$customer['id']}</td>
            <td>{$customer['name']}</td>
            <td>{$customer['email']}</td>
            <td>{$customer['phone']}</td>
            <td>
                <a href='show/{$customer['id']}' class='btn btn-warning'>Edit</a>
                <a href='index.php?action=customers&method=delete&id={$customer['id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a>
            </td>
        </tr>";
}

echo "</tbody></table>";

// require 'app/views/footer.php';
?>
