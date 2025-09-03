<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Staff Account Created</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 0;
    }
    .email-wrapper {
      max-width: 600px;
      margin: 40px auto;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.07);
      overflow: hidden;
    }
    .email-header {
      background-color: #3c767f;
      padding: 20px;
      text-align: center;
      color: #ffffff;
    }
    .email-header h1 {
      margin: 0;
      font-size: 22px;
    }
    .email-body {
      padding: 30px;
      color: #333333;
    }
    .email-body h2 {
      font-size: 18px;
      margin-bottom: 20px;
    }
    .credentials {
      background-color: #f1f3f6;
      padding: 15px;
      border-radius: 6px;
      margin: 20px 0;
    }
    .credentials p {
      margin: 8px 0;
      font-weight: bold;
    }
    .login-btn {
      display: inline-block;
      background-color: #3c767f;
      color: white;
      padding: 12px 25px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      margin-top: 10px;
    }
    .email-footer {
      text-align: center;
      padding: 20px;
      font-size: 13px;
      color: #888;
    }

    @media (max-width: 600px) {
      .email-body {
        padding: 20px;
      }
    }
    
  </style>
</head>
<body>

<div class="email-wrapper">
  <div class="email-header">
    <h1>Staff Account Created</h1>
  </div>
  <div class="email-body">
    <h2>Welcome to {{ $fname }}{{ $lname }}!</h2>
    <p>Your staff account has been successfully created. Below are your login credentials:</p>
    
    <div class="credentials">
      <h5><strong>Email:</strong> <p>{{ $email }}</p></h5>
      <h5><strong>Password:</strong><p> {{ $password }}</p></h5>
    </div>

    <p>To get started, please login using the button below and change your password after your first login.</p>

    <a href="https://headquaters.technofra.com/" class="login-btn">Login Now</a>
  </div>

  <div class="email-footer">
    &copy; {{ date('Y') }} Technofra. All rights reserved.
  </div>
</div>

</body>
</html>
