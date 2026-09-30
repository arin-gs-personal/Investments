<!DOCTYPE html>
<html>
<head>
    <title>Rate Updation Form</title>
    <link rel="icon" type="image/png" href="/images/logo004.png">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            width: 240px;
            background: #111827;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px 20px;
            color: #fff;
            overflow: hidden;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 25px;
            color: #ffffff;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #1f2937;
        }

        .main {
            margin-left: 240px;
            padding: 40px 30px;
            min-height: 100vh;
        }

        .topbar {
            background: #f0f4f8;
            padding: 5px 25px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .topbar p {
            font-size: 18px;
            font-weight: bold;
        }

        .logout-btn {
            bottom: 10px;
            position: relative;
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .form-box {
            max-width: 100%;
            background: #e1e1e2;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .form-box h2 {
            text-align: center;
            color: #111827;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
            color: #111827;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f0f4f8;
        }

        button {
            background: #334770d9;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s;
        }

        button:hover {
            background: #218838;
        }

        .view-btn {
            background: #2563eb;
            padding: 10px 16px;
            margin-right: 10px;
            color: white;
            border-radius: 5px;
            display: inline-block;
            transition: background 0.3s;
        }

        .view-btn:hover {
            background: #1e40af;
        }

        .form-actions {
            margin-bottom: 20px;
        }

    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Rate Updation</h2>
        <a href="/stdinfo">Dashboard</a>
        <a href="/admission">New Purchases</a>
        <a href="/rate" class="active">Update Rate</a>
    </div>

    <div class="main">
        <div class="topbar">
            <p>Hi Investor <strong>{{ auth()->user()->name }}</strong></p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="form-box">
            <h2>Rate Updation Form</h2>

            <form method="POST" action="/rate">
                @csrf
                <label>22k Today Rate</label>
                <input type="number" class="lable" placeholder="₹ {{ $today22 }}" name="today22" required>

                <label>24k Today Rate</label>
                <input type="number" placeholder="₹ {{ $today24 }}" name="today24" required>

                <label>Silver Today Rate</label>
                <input type="number" placeholder="₹ {{ $silver_cost }}" name="silver_cost" required>

                <button type="submit">Submit Rate</button>
            </form>
        </div>
    </div>

</body>
</html>
