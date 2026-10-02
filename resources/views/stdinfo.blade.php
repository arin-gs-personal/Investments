<!DOCTYPE html>
<html>
<head>
    <title>Investment Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/images/logo004.png">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f3f4f6;
        }

        .dashboard {
            display: block;
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

        .main {
            margin-left: 240px;
            padding: 30px;
            min-height: 100vh;
        }


        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            margin-bottom: 10px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #1f2937;
            color: #fff;
        }

    
        .main {
            flex: 1;
            padding: 30px;
        }

    
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .welcome {
            font-size: 20px;
            font-weight: 600;
        }

        .logout-btn {
            background: #ef4444;
            border: none;
            padding: 8px 16px;
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

    
        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 35px;
        }

        .card {
            flex: 1;
            padding: 20px;
            border-radius: 12px;
            color: #fff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .gold22 { background: linear-gradient(135deg,#facc15,#eab308); }
        .gold24 { background: linear-gradient(135deg,#f59e0b,#d97706); }
        .silver { background: linear-gradient(135deg,#9ca3af,#6b7280); }

        .card h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 22px;
            font-weight: bold;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }

        th {
            background: #111827;
            color: #fff;
            padding: 12px;
            font-size: 14px;
        }

        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        tr:hover {
            background: #f9fafb;
        }

        .profit { color: #16a34a; font-weight: 600; }
        .loss { color: #dc2626; font-weight: 600; }

        .footer-row {
            background: #f3f4f6;
            font-weight: bold;
        }

        @media(max-width: 900px) {
            .sidebar { display: none; }
            .cards { flex-direction: column; }
        }
        .btn1 {
            cursor: pointer;
        }

         .table-tools {
        margin-bottom: 12px;
    }

    .search-input {
        width: 100%;
        max-width: 400px;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }

    .search-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    }
    </style>
</head>

<body>

<div class="dashboard">

    <div class="sidebar">
        <h2>INVESTOR PANEL</h2>
        <a href="/stdinfo" class="active">Dashboard</a>
        <a href="/admission">New Purchase</a>
        <a href="/rate">Update Rate</a>
    </div>


    <div class="main">

        
        <div class="topbar">
            <div class="welcome">
                Welcome, {{ auth()->user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        
        <div class="cards">
            <div class="card gold22">
                <h3>22K Gold Rate</h3>
                <p>₹ {{ $today22 }}</p>
            </div>

            <div class="card gold24">
                <h3>24K Gold Rate</h3>
                <p>₹ {{ $today24 }}</p>
            </div>

            <div class="card silver">
                <h3>Silver Rate</h3>
                <p>₹ {{ $silver_cost }}</p>
            </div>
        </div>

        <div class="section-title">22K Gold Purchases</div>

<div class="table-tools">
    <input
        type="text"
        class="search-input table-search"
        data-table="table22"
        placeholder="Search 22K purchases..."
    >
</div>

<table id="table22">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>DOP</th>
            <th>Cost</th>
            <th>Qty</th>
            <th>Per Unit</th>
            <th>Profit</th>
        </tr>
    </thead>

    <tbody>
        @forelse($studentsKind22->sortByDesc('dob') as $index => $student)
        <tr class="purchase-row">
            <td>{{ $index + 1 }}</td>
            <td>{{ $student->student_name }}</td>
            <td data-date="{{ $student->dob }}">
                {{ date('d/m/Y (D)', strtotime($student->dob)) }}
            </td>
            <td>₹{{ number_format($student->cost, 2) }}</td>
            <td>{{ $student->quantity }}g</td>
            <td>
                ₹{{ $student->quantity > 0 ? round($student->cost / $student->quantity, 2) : 0 }}
            </td>
            <td class="{{ $student->profit >= 0 ? 'profit' : 'loss' }}">
                ₹{{ number_format($student->profit, 2) }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No Purchases Found</td>
        </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr class="footer-row">
            <td colspan="6" style="text-align:right;">Total Quantity</td>
            <td>{{ number_format($totalQN22, 2) }}g</td>
        </tr>

        <tr class="footer-row">
            <td colspan="6" style="text-align:right;">Purchase Amount</td>
            <td>₹{{ number_format($totalCostKind22, 2) }}</td>
        </tr>

        <tr class="footer-row">
            <td colspan="6" style="text-align:right;">Present Amount</td>
            <td>₹{{ number_format($today22 * $totalQN22, 2) }}</td>
        </tr>

        <tr class="footer-row">
            <td class="{{ $totalCost22 >= 0 ? 'profit' : 'loss' }}"
                colspan="6"
                style="text-align:right;">
                {{ $totalCost22 > 0 ? 'Total Profit' : 'Total Loss' }}
            </td>

            <td class="{{ $totalCost22 >= 0 ? 'profit' : 'loss' }}">
                ₹{{ number_format($totalCost22, 2) }}
            </td>
        </tr>
    </tfoot>
</table>

        <div class="section-title">24K Gold Purchases</div>

<div class="table-tools">
    <input
        type="text"
        class="search-input table-search"
        data-table="table24"
        placeholder="Search 24K purchases..."
    >
</div>

<table id="table24">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>DOP</th>
            <th>Cost</th>
            <th>Qty</th>
            <th>Per Unit</th>
            <th>Profit</th>
        </tr>
    </thead>

    <tbody>
        @forelse($studentsKind24->sortByDesc('dob') as $index => $student)
        <tr class="purchase-row">
            <td>{{ $index + 1 }}</td>
            <td>{{ $student->student_name }}</td>
            <td data-date="{{ $student->dob }}">
                {{ date('d/m/Y (D)', strtotime($student->dob)) }}
            </td>
            <td>₹{{ number_format($student->cost, 2) }}</td>
            <td>{{ $student->quantity }}g</td>
            <td>
                ₹{{ $student->quantity > 0 ? round($student->cost / $student->quantity, 2) : 0 }}
            </td>
            <td class="{{ $student->profit >= 0 ? 'profit' : 'loss' }}">
                ₹{{ number_format($student->profit, 2) }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No Purchases Found</td>
        </tr>
        @endforelse
    </tbody>

    <tfoot>
        <!-- Keep your existing 24K total rows here -->
    </tfoot>
</table>


        <div class="section-title">Silver Purchases</div>

<div class="table-tools">
    <input
        type="text"
        class="search-input table-search"
        data-table="tableSilver"
        placeholder="Search silver purchases..."
    >
</div>

<table id="tableSilver">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>DOP</th>
            <th>Cost</th>
            <th>Qty</th>
            <th>Per Unit</th>
            <th>Profit</th>
        </tr>
    </thead>

    <tbody>
        @forelse($studentsKindSS->sortByDesc('dob') as $index => $student)
        <tr class="purchase-row">
            <td>{{ $index + 1 }}</td>
            <td>{{ $student->student_name }}</td>
            <td data-date="{{ $student->dob }}">
                {{ date('d/m/Y (D)', strtotime($student->dob)) }}
            </td>
            <td>₹{{ number_format($student->cost, 2) }}</td>
            <td>{{ $student->quantity }}g</td>
            <td>
                ₹{{ $student->quantity > 0 ? round($student->cost / $student->quantity, 2) : 0 }}
            </td>
            <td class="{{ $student->profit >= 0 ? 'profit' : 'loss' }}">
                ₹{{ number_format($student->profit, 2) }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No Purchases Found</td>
        </tr>
        @endforelse
    </tbody>

    <tfoot>
        <!-- Keep your existing Silver total rows here -->
    </tfoot>
</table>


    </div>
</div>


<script>
document.querySelectorAll('.table-search').forEach(function(input) {

    input.addEventListener('input', function() {

        const search = this.value.toLowerCase().trim();
        const table = document.getElementById(this.dataset.table);

        const rows = table.querySelectorAll(
            'tbody tr.purchase-row'
        );

        rows.forEach(function(row) {

            const text = row.textContent.toLowerCase();

            row.style.display =
                text.includes(search) ? '' : 'none';
        });
    });
});
</script>


</body>
</html>
