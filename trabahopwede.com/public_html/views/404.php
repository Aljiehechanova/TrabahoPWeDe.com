<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>404 - Page Not Found</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f5f5f5;
      color: #212529;
      font-family: Arial, sans-serif;
    }

    .error-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      text-align: center;
      flex-direction: column;
    }

    .error-icon {
      font-size: 100px;
      color: #dc3545;
    }

    .error-title {
      font-size: 2.5rem;
      font-weight: bold;
      margin-top: 1rem;
    }

    .error-message {
      font-size: 1.2rem;
      margin-top: 0.5rem;
      margin-bottom: 2rem;
    }

    a.btn:focus {
      outline: 3px solid #000;
      outline-offset: 3px;
    }

    @media (prefers-reduced-motion: reduce) {
      * {
        animation: none !important;
        transition: none !important;
      }
    }
  </style>
</head>
<body>
  <div class="error-container">
    <div class="error-icon" aria-hidden="true">🚫</div>
    <h1 class="error-title">Oops! Page Not Found (404)</h1>
    <p class="error-message">
      Sorry, we couldn’t find the page you were looking for. It may have been moved, renamed, or doesn't exist.
    </p>
    <a href="/home" class="btn btn-primary btn-lg" role="button">🔙 Return to Home</a>
  </div>
</body>
</html>
