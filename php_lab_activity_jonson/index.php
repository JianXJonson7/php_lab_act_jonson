<?php
include 'dbcon.php';

if (isset($_POST['add_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (!empty($name) && !empty($email)) {
    
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);

        if ($stmt->execute()) {
            header("Location: index.php?msg=added");
            exit();
        } else {
            $error = "Error adding record: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}


$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="text-center mb-4">User Management System</h2>

    
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] == 'added'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                User successfully added!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                User record updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                User record deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-warning"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white text-black">
                    <h5 class="card-title mb-0">Add New User</h5>
                </div>
                <div class="card-body">
                    <form action="index.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Full Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Address" required>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white text-black">
                    <h5 class="card-title mb-0">Registered Users</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-white">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                            <td><?php echo date('M d, Y g:i A', strtotime($row['created at'])); ?></td>
                                            <td class="text-center">
                                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Update</a>
                                                <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                                   Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No data found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>