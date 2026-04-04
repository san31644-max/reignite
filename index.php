<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Restricted</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .container {
            text-align: center;
            background: rgba(0, 0, 0, 0.4);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            max-width: 400px;
        }

        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            margin-bottom: 25px;
            color: #ddd;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #ff4b5c;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #ff1f3a;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="icon">🔒</div>
        <h1>Access Restricted</h1>
        <p>You can't access this page. It is restricted or requires special permissions.</p>
        <a href="#" class="btn">Go Back</a>
    </div>

</body>
</html>