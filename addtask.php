<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Detail Page</title>
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
        .task-detail label {
            display: block;
            margin-bottom: 5px;
        }
        .task-detail input[type="text"],
        .task-detail textarea,
        .task-detail select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .task-detail input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .task-detail input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .return-btn {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .return-btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="task-detail">
        <h2>Add New Task</h2>
        <form id="add-task-form" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
            <label for="deadline">Deadline:</label>
            <input type="text" id="deadline" name="deadline" placeholder="YYYY-MM-DD" required>
            <label for="priority">Priority:</label>
            <select id="priority" name="priority" required>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
            </select>
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
            <input type="submit" value="Add Task">
        </form>
        <a href="dashboard.php" class="return-btn">Return to Dashboard</a>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Process form submission
        $title = $_POST["title"];
        $description = $_POST["description"];
        $deadline = $_POST["deadline"];
        $priority = $_POST["priority"];
        $category = $_POST["category"];

        // Perform database operations (Insertion)
        $conn = new mysqli("localhost", "root", "", "task_manager");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO tasks (title, description, deadline, priority, category, completed) 
                VALUES ('$title', '$description', '$deadline', '$priority', '$category',0)";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='task-detail'>";
            echo "<p>New task added successfully!</p>";
            echo "</div>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>
