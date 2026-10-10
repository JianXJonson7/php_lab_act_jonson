<?php
include 'dbcon.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

if (isset($_POST['update_user'])) {
    $name = trim($_POST['Name']);
    $email = trim($_POST['Email']);

    if (!empty($name) && !empty($email)) {
        $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $name, $email, $id);

        if ($update_stmt->execute()) {
            header("Location: index.php?msg=updated");
            exit();
        } else {
            $error = "Error updating record: " . $conn->error;
        }
        $update_stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="card-title mb-0">Update User Record</h5>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="Name" id="Name" class="form-control" value="<?php echo htmlspecialchars($user['Name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="Email" name="Email" id="Email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" name="update_user" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>