<!DOCTYPE html>
<html>
<head>
    <title>Purchase Entry</title>
    <link rel="icon" type="image/png" href="/images/logo004.png">
    <style>
        /* ===== Global Styles ===== */
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

        /* ===== Sidebar ===== */
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
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin: auto;
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

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 16px;
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

        .error-list {
            color: red;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            margin-bottom: 15px;
            text-align: center;
        }

        .form-columns {
            display: flex;
            gap: 20px;
        }

        .form-columns > div {
            flex: 1;
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>New Purchase</h2>
        <a href="/stdinfo">Dashboard</a>
        <a href="/admission" class="active">New Purchases</a>
        <a href="/rate">Update Rate</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <!-- Topbar -->
        <div class="topbar">
            <p>Hi Investor <strong>{{ auth()->user()->name }}</strong></p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <!-- Form Box -->
        <div class="form-box">
            <h2>Purchase Entry</h2>

            <!-- Display Errors -->
            @if ($errors->any())
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <!-- Success Message -->
            @if(session('success'))
                <p class="success">{{ session('success') }}</p>
            @endif


            <!-- Form Columns -->
            <form method="POST" action="/admission">
                @csrf
                <div class="form-columns">
                    <div>
                        <label>Customer Name</label>
                        <input type="text" name="student_name" placeholder="Enter Name" required>

                        <label>Date of Purchase</label>
                        <input type="date" name="dob" required>

                        <label>Select Metal</label>
                        <select name="metal" required>
                            <option disabled selected>-- Select Metal --</option>
                            <option style="color:#ff9500;">GG</option>
                            <option style="color:rgb(0,17,255);">SS</option>
                        </select>

                        <label>Phone Number</label>
                        <input type="tel" name="phone" placeholder="📞 Phone" minlength="10" required>
                    </div>

                    <div>
                        <label>Select Quantity (in g)</label>
                        <input type="number" name="quantity" placeholder="Weight in Grams" required>

                        <label>Amount</label>
                        <input type="number" name="cost" placeholder="₹ rate" required>

                        <label>Select Kind</label>
                        <select name="kind" required>
                            <option disabled selected>-- Select Kind --</option>
                            <option>22</option>
                            <option>24</option>
                            <option>99</option>
                        </select>

                        <label>Address</label>
                        <select name="address" required>
                            <option disabled selected>-- 🛍️ Shop District --</option>
                            <option value="Ariyalur">Ariyalur</option>
                            <option value="Chengalpattu">Chengalpattu</option>
                            <option value="Chennai">Chennai</option>
                            <option value="Coimbatore">Coimbatore</option>
                            <option value="Cuddalore">Cuddalore</option>
                            <option value="Dharmapuri">Dharmapuri</option>
                            <option value="Dindigul">Dindigul</option>
                            <option value="Erode">Erode</option>
                            <option value="Kallakurichi">Kallakurichi</option>
                            <option value="Kanchipuram">Kanchipuram</option>
                            <option value="Kanyakumari">Kanyakumari</option>
                            <option value="Karur">Karur</option>
                            <option value="Krishnagiri">Krishnagiri</option>
                            <option value="Madurai">Madurai</option>
                            <option value="Mayiladuthurai">Mayiladuthurai</option>
                            <option value="Nagapattinam">Nagapattinam</option>
                            <option value="Namakkal">Namakkal</option>
                            <option value="Nilgiris">Nilgiris</option>
                            <option value="Perambalur">Perambalur</option>
                            <option value="Pudukkottai">Pudukkottai</option>
                            <option value="Ramanathapuram">Ramanathapuram</option>
                            <option value="Ranipet">Ranipet</option>
                            <option value="Salem">Salem</option>
                            <option value="Sivaganga">Sivaganga</option>
                            <option value="Tenkasi">Tenkasi</option>
                            <option value="Thanjavur">Thanjavur</option>
                            <option value="Theni">Theni</option>
                            <option value="Thoothukudi">Thoothukudi</option>
                            <option value="Tiruchirappalli">Tiruchirappalli</option>
                            <option value="Tirunelveli">Tirunelveli</option>
                            <option value="Tirupathur">Tirupathur</option>
                            <option value="Tiruppur">Tiruppur</option>
                            <option value="Tiruvallur">Tiruvallur</option>
                            <option value="Tiruvannamalai">Tiruvannamalai</option>
                            <option value="Tiruvarur">Tiruvarur</option>
                            <option value="Vellore">Vellore</option>
                            <option value="Viluppuram">Viluppuram</option>
                            <option value="Virudhunagar">Virudhunagar</option>
                        </select>
                    </div>
                </div>

                <button type="submit">Submit Purchase</button>
            </form>
        </div>
    </div>

</body>
</html>
