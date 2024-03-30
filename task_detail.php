<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .task-detail {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .task-detail h2 {
            margin-top: 0;
        }
        .task-detail p {
            margin: 5px 0;
        }
        .edit-btn, .mark-done-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        .edit-btn:hover, .mark-done-btn:hover {
            background-color: #0056b3;
        }
        .return-btn {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .return-btn:hover {
            background-color: #555;
        }
        .completed {
            color: green;
        }
        .pending {
            color: red;
        }
    </style>
</head>
<body>
    <div class="task-detail">
        <h2>Task Detail</h2>
        <?php
        // Function to mark task as done
        function markTaskAsDone($taskId) {
            // Connect to the database
            $conn = new mysqli("localhost", "root", "", "task_manager");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Update task status to completed
            $sql = "UPDATE tasks SET completed = 1 WHERE id = $taskId";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='completed'>Task marked as done successfully!</p>";
            } else {
                echo "Error updating record: " . $conn->error;
            }

            // Close connection
            $conn->close();
        }

        // Check if "Mark as Done" button is clicked
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_done'])) {
            $taskId = $_GET['id']; // Assuming 'id' is passed in the URL
            markTaskAsDone($taskId);
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
            echo "<p><strong>Title:</strong> " . $row['title'] . "</p>";
            echo "<p><strong>Description:</strong> " . $row['description'] . "</p>";
            echo "<p><strong>Deadline:</strong> " . $row['deadline'] . "</p>";
            echo "<p><strong>Priority:</strong> " . $row['priority'] . "</p>";
            echo "<p><strong>Category:</strong> " . $row['category'] . "</p>";

            // Display status (pending or completed)
            $status = ($row['completed'] == 1) ? "Completed" : "Pending";
            $statusClass = ($row['completed'] == 1) ? "completed" : "pending";
            echo "<p><strong>Status:</strong> <span class='$statusClass'>$status</span></p>";

            // Mark as Done button
            if ($row['completed'] == 0) {
                echo "<form method='post'><button type='submit' name='mark_done' class='mark-done-btn'>Mark as Done</button></form>";
            }

            // Edit button (displayed only if task is not completed)
            if ($row['completed'] == 0) {
                echo "<a href='edit_task.php?id=$taskId' class='edit-btn'>Edit</a>";
            }
        } else {
            echo "<p>No task found with the given ID.</p>";
        }

        $conn->close();
        ?>
        <a href="dashboard.php" class="return-btn">Return to Dashboard</a>
    </div>
</body>
</html>
