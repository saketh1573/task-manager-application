<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .overview-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .task-box {
            width: 200px; /* Adjust width as needed */
            height: 200px; /* Adjust height as needed */
            justify-content: center;
            align-items: center;
            border-radius: 10px; /* Adjust border radius for square shape */
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #tasks-left {
            background-color: #ff7f7f;
        }
        #tasks-completed {
            background-color: #7fff7f;
        }
        .task-box:hover {
            background-color: #f0f0f0;
        }
        .task-box p {
            margin: 0;
            font-size: 78px; /* Adjust font size as needed */
            text-align: center;
            font-weight: bold;
            line-height: 1; /* Ensure proper vertical alignment */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .task {
            display: none;
            background-color: #fff;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .task:hover {
            background-color: #f0f0f0;
        }
        .add-task-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .add-task-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="overview-container">
        <div class="task-box" id="tasks-left" onclick="displayTasksLeft()">
            <h3>Tasks Left</h3>
            <p id="tasks-left-count"><?php echo getTasksLeftCount(); ?></p>
        </div>
        <div class="task-box" id="tasks-completed" onclick="displayCompletedTasks()">
            <h3>Tasks Completed</h3>
            <p id="tasks-completed-count"><?php echo getTasksCompletedCount(); ?></p>
        </div>
    </div>
    <div id="task-list">
        <?php displayAllTasks(); ?>
    </div>
    <a href="addtask.php" class="add-task-btn">Add New Task</a>

    <script>
        function displayTasksLeft() {
            var tasksLeft = document.getElementsByClassName('task-left');
            var tasksCompleted = document.getElementsByClassName('task-completed');
            for (var i = 0; i < tasksLeft.length; i++) {
                tasksLeft[i].style.display = 'table-row';
            }
            for (var i = 0; i < tasksCompleted.length; i++) {
                tasksCompleted[i].style.display = 'none';
            }
        }

        function displayCompletedTasks() {
            var tasksLeft = document.getElementsByClassName('task-left');
            var tasksCompleted = document.getElementsByClassName('task-completed');
            for (var i = 0; i < tasksCompleted.length; i++) {
                tasksCompleted[i].style.display = 'table-row';
            }
            for (var i = 0; i < tasksLeft.length; i++) {
                tasksLeft[i].style.display = 'none';
            }
        }

        function redirectToTaskDetail(taskId) {
            window.location.href = 'task_detail.php?id=' + taskId;
        }
    </script>
</body>
</html>
<?php
// PHP functions to fetch and display task data

function getTasksLeftCount() {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "task_manager");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get count of tasks left
    $sql = "SELECT COUNT(*) AS count FROM tasks WHERE completed = 0";
    $result = $conn->query($sql);

    // Get count
    $count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row["count"];
    }

    // Close connection
    $conn->close();

    return $count;
}

function getTasksCompletedCount() {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "task_manager");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get count of completed tasks
    $sql = "SELECT COUNT(*) AS count FROM tasks WHERE completed = 1";
    $result = $conn->query($sql);

    // Get count
    $count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row["count"];
    }

    // Close connection
    $conn->close();

    return $count;
}

function displayTasksLeft() {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "task_manager");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch tasks left
    $sql = "SELECT * FROM tasks WHERE completed = 0";
    $result = $conn->query($sql);

    // Display tasks left
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Title</th><th>Description</th><th>Deadline</th><th>Priority</th><th>Category</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr class='task task-left' onclick='redirectToTaskDetail(" . $row['id'] . ")'>";
            echo "<td>" . $row['title'] . "</td>";
            // Truncate description if it exceeds 50 characters
            $description = strlen($row['description']) > 50 ? substr($row['description'], 0, 50) . '...' : $row['description'];
            echo "<td>" . $description . "</td>";
            echo "<td>" . $row['deadline'] . "</td>";
            echo "<td>" . $row['priority'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No tasks left.</p>";
    }

    // Close connection
    $conn->close();
}

function displayAllTasks() {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "task_manager");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch all tasks
    $sql = "SELECT * FROM tasks";
    $result = $conn->query($sql);

    // Display tasks
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Title</th><th>Description</th><th>Deadline</th><th>Priority</th><th>Category</th></tr>";
        while ($row = $result->fetch_assoc()) {
            if ($row['completed'] == 0) {
                echo "<tr class='task task-left' onclick='redirectToTaskDetail(" . $row['id'] . ")'>";
            } else {
                echo "<tr class='task task-completed' onclick='redirectToTaskDetail(" . $row['id'] . ")'>";
            }
            echo "<td>" . $row['title'] . "</td>";
            // Truncate description if it exceeds 50 characters
            $description = strlen($row['description']) > 50 ? substr($row['description'], 0, 50) . '...' : $row['description'];
            echo "<td>" . $description . "</td>";
            echo "<td>" . $row['deadline'] . "</td>";
            echo "<td>" . $row['priority'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No tasks found.</p>";
    }

    // Close connection
    $conn->close();
}

?>
