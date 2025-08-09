<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Welcome</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      background: #f7f7f7;
      text-align: center;
    }
    a.btn {
      display: inline-block;
      padding: 12px 24px;
      margin: 10px;
      text-decoration: none;
      font-weight: 600;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      user-select: none;
      border: 2px solid transparent;
    }
    .btn-secondary {
      background-color: #6c757d;
      color: #fff;
      border-color: #6c757d;
    }
    .btn-secondary:hover,
    .btn-secondary:focus {
      background-color: #5a6268;
      border-color: #545b62;
      color: #fff;
    }
  </style>
</head>
<body>
  <h1>First Login or Register</h1>
  <a href="register.php" class="btn btn-secondary">Register</a>
  <a href="login.php" class="btn btn-secondary">Login</a>  
</body>
</html>
