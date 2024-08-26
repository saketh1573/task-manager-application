<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .edit-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .edit-form input[type="text"], .edit-form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .edit-form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="edit-form">
        <h2>Edit Task</h2>
        <?php
        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $taskId = $_POST['task_id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $deadline = $_POST['deadline'];
            $priority = $_POST['priority'];
            $category = $_POST['category'];

            // Update task details in the database
            $conn = new mysqli("localhost", "root", "", "task_manager");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

// Prepare the SQL statement
$sql = "UPDATE tasks SET title=?, description=?, deadline=?, priority=?, category=? WHERE id=?";
$stmt = $conn->prepare($sql);

// Bind parameters and execute the statement
if ($stmt->bind_param("sssssi", $_POST['title'], $_POST['description'], $_POST['deadline'], $_POST['priority'], $_POST['category'], $_POST['task_id']) && $stmt->execute()) {
    echo "Task updated successfully.";
    // Redirect back to task detail page
    header("Location: task_detail.php?id=" . $_POST['task_id']);
    exit;
} else {
    echo "Error updating task: " . $stmt->error;
}

// Close the statement
$stmt->close();

            if ($conn->query($sql) === TRUE) {
                echo "Task updated successfully.";
                // Redirect back to task detail page
                header("Location: task_detail.php?id=$taskId");
                exit;
            } else {
                echo "Error updating task: " . $conn->error;
            }

            $conn->close();
        }

        // Retrieve task details from database based on task ID
        $taskId = $_GET['id']; // Assuming 'id' is passed in the URL
        $conn = new mysqli("localhost", "root", "", "task_manager");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM tasks WHERE id = $taskId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <form method="post" class="edit-form">
                <input type="hidden" name="task_id" value="<?php echo $taskId; ?>">
                <label for="title">Title:</label>
                <input type="text" name="title" value="<?php echo $row['title']; ?>">
                <label for="description">Description:</label>
                <textarea name="description"><?php echo $row['description']; ?></textarea>
                <label for="deadline">Deadline:</label>
                <input type="text" name="deadline" value="<?php echo $row['deadline']; ?>">
                <label for="priority">Priority:</label>
                <input type="text" name="priority" value="<?php echo $row['priority']; ?>">
                <label for="category">Category:</label>
                <input type="text" name="category" value="<?php echo $row['category']; ?>">
                <input type="submit" value="Submit">
            </form>
            <?php
        } else {
            echo "<p>No task found with the given ID.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
