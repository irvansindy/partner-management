<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .card {
            border: none;
            border-radius: 24px;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 20px 50px rgba(38, 78, 121, 0.12);
        }

        .card-body {
            padding: 3rem;
        }

        .status-circle {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .status-circle i {
            color: #28a745;
            font-size: 2rem;
        }

        h2 {
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #1f2a37;
        }

        .text-muted {
            color: #6c7a89 !important;
            line-height: 1.75;
        }

        .btn-primary {
            background-color: #1b4b7b;
            border-color: #1b4b7b;
            padding: 0.85rem 1.6rem;
            border-radius: 12px;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #163f68;
            border-color: #163f68;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-body text-center">
            <div class="status-circle">
                <span style="font-size: 2rem; color: #28a745;">✓</span>
            </div>
            <h2>Berhasil</h2>
            <p class="text-muted mb-4">
                Tanggapan Anda telah berhasil dicatat. Tim kami akan meninjaunya dan menghubungi Anda jika diperlukan tindak lanjut.
            </p>
            <a href="/" class="btn btn-primary">Back to Homepage</a>
        </div>
    </div>
</body>

</html>
