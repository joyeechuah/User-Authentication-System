<?php
session_start();

$isLoggedIn = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Todo List</title>

    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            background: #f1f3f5;
            margin: 0;
            padding: 40px 16px;
        }

        .card {
            max-width: 560px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 40px;
        }

        .welcome {
            margin: 0 0 24px;
            color: #6c757d;
            font-size: 16px;
        }

        .auth-links a {
            font-size: 26px;
            color: #0d6efd;
            margin-right: 28px;
        }

        .task-list {
            list-style: none;
            margin: 0 0 24px;
            padding: 0;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .task-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 16px;
            border-bottom: 1px solid #dee2e6;
        }

        .task-item:last-child {
            border-bottom: 0;
        }

        .task-text {
            flex: 1;
            font-size: 20px;
        }

        .task-text.done {
            text-decoration: line-through;
            color: #495057;
        }

        .task-check {
            appearance: none;
            -webkit-appearance: none;
            width: 40px;
            height: 40px;
            margin: 0;
            border: 2px solid #adb5bd;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            flex-shrink: 0;
        }

        .task-check:checked {
            background: #198754 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='%23fff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 8.5l3.5 3.5L13 5'/%3E%3C/svg%3E") center / 22px no-repeat;
            border-color: #198754;
        }

        .delete-btn {
            background: #dc3545;
            border: 0;
            border-radius: 8px;
            width: 44px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .delete-btn:hover {
            background: #bb2d3b;
        }

        .delete-btn svg {
            width: 18px;
            height: 18px;
            stroke: #fff;
        }

        .add-row {
            display: flex;
            gap: 12px;
        }

        .add-row input {
            flex: 1;
            padding: 14px 16px;
            font-size: 18px;
            border: 1px solid #ced4da;
            border-radius: 8px;
        }

        .add-row input::placeholder {
            color: #adb5bd;
        }

        .add-row button {
            background: #0d6efd;
            color: #fff;
            border: 0;
            border-radius: 8px;
            padding: 0 24px;
            font-size: 18px;
            cursor: pointer;
        }

        .add-row button:hover {
            background: #0b5ed7;
        }

        .logout {
            display: block;
            text-align: center;
            margin-top: 28px;
            font-size: 22px;
            color: #0d6efd;
        }
    </style>
</head>
<body>

<?php if (!$isLoggedIn): ?>

    <div class="card">
        <h1>My Todo List</h1>
        <p class="auth-links">
            <a href="login_page.php">Login</a>
            <a href="signup_page.php">Sign Up</a>
        </p>
    </div>

<?php else: ?>

    <div class="card">
        <h1>My Todo List</h1>
        <p class="welcome">
            Welcome, <?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>
        </p>

        <ul class="task-list" id="taskList">
            <li class="task-item">
                <input type="checkbox" class="task-check" checked>
                <span class="task-text done">Task 1</span>
                <button type="button" class="delete-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/>
                    </svg>
                </button>
            </li>
            <li class="task-item">
                <input type="checkbox" class="task-check">
                <span class="task-text">Task 2</span>
                <button type="button" class="delete-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/>
                    </svg>
                </button>
            </li>
            <li class="task-item">
                <input type="checkbox" class="task-check">
                <span class="task-text">Task 3</span>
                <button type="button" class="delete-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/>
                    </svg>
                </button>
            </li>
        </ul>

        <div class="add-row">
            <input type="text" id="newTask" placeholder="Add new item...">
            <button type="button" id="addBtn">Add</button>
        </div>
    </div>

    <a class="logout" href="logout_page.php">Logout</a>

    <script>
        const taskList = document.getElementById('taskList');
        const newTask  = document.getElementById('newTask');
        const addBtn   = document.getElementById('addBtn');

        taskList.addEventListener('click', function (event) {
            const deleteBtn = event.target.closest('.delete-btn');
            if (deleteBtn) {
                deleteBtn.closest('.task-item').remove();
            }
        });

        taskList.addEventListener('change', function (event) {
            if (event.target.classList.contains('task-check')) {
                const text = event.target.nextElementSibling;
                text.classList.toggle('done', event.target.checked);
            }
        });

        function addTask() {
            const title = newTask.value.trim();
            if (title === '') {
                return;
            }

            const item = document.createElement('li');
            item.className = 'task-item';
            item.innerHTML =
                '<input type="checkbox" class="task-check">' +
                '<span class="task-text"></span>' +
                '<button type="button" class="delete-btn">' +
                    '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" ' +
                         'stroke-linecap="round" stroke-linejoin="round">' +
                        '<path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/>' +
                    '</svg>' +
                '</button>';

            item.querySelector('.task-text').textContent = title;

            taskList.appendChild(item);
            newTask.value = '';
            newTask.focus();
        }

        addBtn.addEventListener('click', addTask);

        newTask.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                addTask();
            }
        });
    </script>

<?php endif; ?>

</body>
</html>
