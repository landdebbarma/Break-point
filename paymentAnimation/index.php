<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .payment-success {
            max-width: 400px;
            margin: 100px auto;
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: slideUp 1s ease-out;
        }

        .checkmark-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background:rgb(53,213,226);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            animation: popIn 0.5s ease-out;
        }

        .checkmark {
            color: white;
            font-size: 3rem;
            animation: bounce 0.5s ease;
        }

        @keyframes popIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .text1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .text2 {
            font-size: 1rem;
            color: #6c757d;
        }

        .btn-home {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="payment-success">
        <div class="checkmark-circle">
            <i class="bi bi-check-lg checkmark"></i>
        </div>
        <div class="text1">Payment Successful</div>
        <div class="text2">Thank you So Much :)</div>
        <a href="../index.html" class="btn btn-home mt-3 text-light" style="background:rgb(53,213,226);">Go to Home</a>
    </div>

</body>

</html>