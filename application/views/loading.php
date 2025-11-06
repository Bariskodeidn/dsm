<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <!-- Bootstrap CSS CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Google Fonts - Inter for a clean look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Custom CSS for the spinner animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner-custom {
            border: 8px solid #f3f3f3;
            /* Light grey base for the spinner circle */
            border-top: 8px solid #0d6efd;
            /* Bootstrap primary blue for the spinning part */
            border-radius: 50%;
            /* Makes it a perfect circle */
            width: 60px;
            /* Width of the spinner */
            height: 60px;
            /* Height of the spinner */
            animation: spin 1s linear infinite;
            /* Applies the spin animation */
        }

        /* Body styling to center the loading content on the page */
        body {
            font-family: 'Inter', sans-serif;
            /* Apply Inter font */
            min-height: 100vh;
            /* Ensure body takes full viewport height */
            display: flex;
            /* Use flexbox for centering */
            align-items: center;
            /* Vertically center content */
            justify-content: center;
            /* Horizontally center content */
            background-color: #f8f9fa;
            /* Bootstrap's default light background color */
            margin: 0;
            /* Remove default body margin */
        }

        /* Card styling for a visually appealing container */
        .card {
            border-radius: 0.75rem;
            /* Slightly more rounded corners for the card */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            /* Soft shadow for depth */
        }
    </style>
</head>

<body>
    <!-- Main container for the loading content, styled as a Bootstrap card -->
    <div class="card p-5 text-center">
        <div class="card-body d-flex flex-column align-items-center">
            <!-- Custom Loading Spinner -->
            <div class="spinner-custom mb-4"></div>

            <!-- Loading Text -->
            <p class="text-secondary fs-5 fw-semibold">Loading...</p>

            <!-- Optional: You can add a more descriptive message or a progress bar here -->
            <!-- <p class="text-muted mt-2">Please wait while we prepare your data.</p> -->
        </div>
    </div>

    <!-- Bootstrap JS Bundle (optional, only needed if you plan to use Bootstrap's JavaScript components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>