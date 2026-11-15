@php
$makePurchaseRows = static function ($students) {
    return $students->sortByDesc('dob')->values()->map(static function ($student, $index) {
        $quantity = (float) $student->quantity;
        $perUnit = $quantity > 0 ? round((float) $student->cost / $quantity, 2) : 0;

        return [
            'id' => $index + 1,
            'parentId' => -1,
            'name' => $student->student_name,
            'date' => $student->dob,
            'cost' => (float) $student->cost,
            'quantity' => $quantity,
            'perUnit' => $perUnit,
            'profit' => (float) $student->profit,
            'searchText' => implode(' ', [
                $student->student_name,
                date('d/m/Y (D)', strtotime($student->dob)),
                number_format($student->cost, 2),
                $quantity . 'g',
                $perUnit,
                number_format($student->profit, 2),
            ]),
        ];
    });
};
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Investment Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/images/logo004.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/styles/jqx.base.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/styles/jqx.light.css">

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
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            background: #111827;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 28px 18px;
            color: #fff;
            overflow-y: auto;
            z-index: 10;
        }

        .main {
            margin-left: 240px;
            padding: 30px;
            min-height: 100vh;
            width: calc(100% - 240px);
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
            gap: 16px;
            margin-bottom: 28px;
            padding-bottom: 18px;
            border-bottom: 1px solid #e5e7eb;
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
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .card {
            min-width: 0;
            padding: 22px;
            border-radius: 14px;
            color: #fff;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
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
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 5px 0 12px;
        }

        .purchase-treegrid {
            width: 100%;
            min-width: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(15, 23, 42, 0.05);
            margin-bottom: 12px;
        }

        .purchase-treegrid .jqx-grid-cell,
        .purchase-treegrid .jqx-tree-grid-cell {
            color: #374151;
        }

        .purchase-treegrid .jqx-widget-header {
            color: #111827;
            background: #abc0d5;
        }

        .purchase-treegrid .jqx-fill-state-pressed,
        .purchase-treegrid .jqx-fill-state-pressed:hover {
            background: #e0e7ff;
            border-color: #c7d2fe;
            color: #1e40af;
        }

        .purchase-treegrid .jqx-grid-column-header {
            font-weight: 600;
        }

        .profit { color: #16a34a; font-weight: 600; }
        .loss { color: #dc2626; font-weight: 600; }

        .purchase-summary {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            margin: 0 0 38px;
            padding: 8px 14px;
            background: #fff;
            font-weight: 600;
            border: 1px solid #e5e7eb;
            border-top: 0;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 5px 15px rgba(15, 23, 42, 0.04);
        }

        .summary-label,
        .summary-value {
            padding: 11px 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-label:last-of-type,
        .summary-value:last-of-type {
            border-bottom: 0;
        }

        .summary-label {
            text-align: right;
            color: #64748b;
        }

        .summary-value {
            min-width: 150px;
            text-align: right;
            color: #111827;
            font-variant-numeric: tabular-nums;
        }

        .empty-purchases {
            padding: 14px;
            margin-bottom: 12px;
            background: #fff;
            color: #6b7280;
            text-align: center;
        }

        @media (max-width: 1000px) {
            .sidebar { width: 205px; }
            .main {
                margin-left: 205px;
                width: calc(100% - 205px);
                padding: 24px;
            }
            .cards { gap: 14px; }
        }

        @media (max-width: 760px) {
            .sidebar { display: none; }
            .main {
                margin-left: 0;
                width: 100%;
                padding: 18px 14px;
            }
            .cards { grid-template-columns: 1fr; }
            .topbar { align-items: flex-start; }
            .welcome { font-size: 18px; }
            .purchase-summary {
                grid-template-columns: minmax(0, 1fr) auto;
                padding: 6px 10px;
            }
            .summary-value { min-width: 110px; }
        }

        .table-tools {
            display: flex;
            justify-content: flex-end;
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

        {{-- <div class="section-title">22K Gold Purchases</div> --}}

<div class="table-tools">
    <div class="section-title" style="margin-right: auto;">22K Gold Purchases</div>
    <input
        type="text"
        class="search-input table-search"
        data-grid="table22"
        aria-label="Search 22K purchases"
        placeholder="Search 22K purchases..."
    >
</div>

<div id="table22" class="purchase-treegrid" aria-label="22K Gold Purchases"></div>
@if($studentsKind22->isEmpty())
    <div class="empty-purchases">No Purchases Found</div>
@endif
<div class="purchase-summary">
    <div class="summary-label">Total Quantity</div>
    <div class="summary-value">{{ number_format($totalQN22, 2) }}g</div>
    <div class="summary-label">Purchase Amount</div>
    <div class="summary-value">₹{{ number_format($totalCostKind22, 2) }}</div>
    <div class="summary-label">Present Amount</div>
    <div class="summary-value">₹{{ number_format($today22 * $totalQN22, 2) }}</div>
    <div class="summary-label {{ $totalCost22 >= 0 ? 'profit' : 'loss' }}">
        {{ $totalCost22 > 0 ? 'Total Profit' : ($totalCost22 < 0 ? 'Total Loss' : 'Net Result') }}
    </div>
    <div class="summary-value {{ $totalCost22 >= 0 ? 'profit' : 'loss' }}">
        ₹{{ number_format($totalCost22, 2) }}
    </div>
</div>

        {{-- <div class="section-title">24K Gold Purchases</.section-title {div> --}}

<div class="table-tools">
    <div class="section-title" style="margin-right: auto;">24K Gold Purchases</div>
    <input
        type="text"
        class="search-input table-search"
        data-grid="table24"
        aria-label="Search 24K purchases"
        placeholder="Search 24K purchases..."
    >
</div>

<div id="table24" class="purchase-treegrid" aria-label="24K Gold Purchases"></div>
@if($studentsKind24->isEmpty())
    <div class="empty-purchases">No Purchases Found</div>
@endif
<div class="purchase-summary">
    <div class="summary-label">Total Quantity</div>
    <div class="summary-value">{{ number_format($totalQN24, 2) }}g</div>
    <div class="summary-label">Purchase Amount</div>
    <div class="summary-value">₹{{ number_format($totalCostKind24, 2) }}</div>
    <div class="summary-label">Present Amount</div>
    <div class="summary-value">₹{{ number_format($today24 * $totalQN24, 2) }}</div>
    <div class="summary-label {{ $totalCost24 >= 0 ? 'profit' : 'loss' }}">
        {{ $totalCost24 > 0 ? 'Total Profit' : ($totalCost24 < 0 ? 'Total Loss' : 'Net Result') }}
    </div>
    <div class="summary-value {{ $totalCost24 >= 0 ? 'profit' : 'loss' }}">
        ₹{{ number_format($totalCost24, 2) }}
    </div>
</div>


        {{-- <div class="section-title">Silver Purchases</div> --}}

<div class="table-tools">
    <div class="section-title" style="margin-right: auto;">Silver Purchases</div>
    <input
        type="text"
        class="search-input table-search"
        data-grid="tableSilver"
        aria-label="Search silver purchases"
        placeholder="Search silver purchases..."
    >
</div>

<div id="tableSilver" class="purchase-treegrid" aria-label="Silver Purchases"></div>
@if($studentsKindSS->isEmpty())
    <div class="empty-purchases">No Purchases Found</div>
@endif
<div class="purchase-summary">
    <div class="summary-label">Total Quantity</div>
    <div class="summary-value">{{ number_format($totalSS, 2) }}g</div>
    <div class="summary-label">Purchase Amount</div>
    <div class="summary-value">₹{{ number_format($totalCostKindSS, 2) }}</div>
    <div class="summary-label">Present Amount</div>
    <div class="summary-value">₹{{ number_format($silver_cost * $totalSS, 2) }}</div>
    <div class="summary-label {{ $totalCostSS >= 0 ? 'profit' : 'loss' }}">
        {{ $totalCostSS > 0 ? 'Total Profit' : ($totalCostSS < 0 ? 'Total Loss' : 'Net Result') }}
    </div>
    <div class="summary-value {{ $totalCostSS >= 0 ? 'profit' : 'loss' }}">
        ₹{{ number_format($totalCostSS, 2) }}
    </div>
</div>


    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxcore.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxdata.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxbuttons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxscrollbar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxmenu.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxlistbox.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxdropdownlist.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxgrid.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxgrid.selection.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxgrid.sort.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxgrid.pager.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxgrid.filter.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jqwidgets-scripts@17.0.0/jqwidgets/jqxgrid.columnsresize.js"></script>
<script>
function formatAmount(value, minimumFractionDigits) {
    return new Intl.NumberFormat('en-IN', {
        minimumFractionDigits: minimumFractionDigits,
        maximumFractionDigits: 2
    }).format(Number(value) || 0);
}

function escapeHtml(value) {
    return $('<div>').text(value == null ? '' : value).html();
}

function renderPurchaseDate(value) {
    if (!value) {
        return '';
    }

    const date = value instanceof Date
        ? value
        : new Date(String(value).slice(0, 10) + 'T00:00:00Z');
    const year = date.getUTCFullYear();
    const month = date.getUTCMonth() + 1;
    const day = date.getUTCDate();
    const weekday = new Intl.DateTimeFormat('en', {
        weekday: 'short',
        timeZone: 'UTC'
    }).format(date);

    return `${String(day).padStart(2, '0')}/${String(month).padStart(2, '0')}/${year} (${weekday})`;
}


function initializePurchaseGrid(elementId, rows) {

    const source = {
        datatype: 'array',
        datafields: [
            { name: 'id', type: 'number' },
            { name: 'name', type: 'string' },
            { name: 'date', type: 'date', format: 'yyyy-MM-dd' },
            { name: 'cost', type: 'number' },
            { name: 'quantity', type: 'number' },
            { name: 'perUnit', type: 'number' },
            { name: 'profit', type: 'number' },
            { name: 'searchText', type: 'string' }
        ],
        id: 'id',
        localdata: rows
    };

    const grid = $('#' + elementId);

    grid.jqxGrid({
        width: '100%',
        autoheight: true,

        source: new $.jqx.dataAdapter(source),

        pageable: true,
        pagesize: 5,
        pagesizeoptions: ['5', '10', '20'],
        pagermode: 'default',
        pagerheight: 45,

        sortable: true,
        filterable: true,
        columnsresize: true,
        altrows: true,
        autorowheight: true,

        columns: [
            {
                text: 'ID',
                datafield: 'id',
                width: 65,
                cellsalign: 'center',
                align: 'center'
            },
            {
                text: 'Name',
                datafield: 'name',
                minwidth: 140,
                cellsrenderer: function (row, column, value) {
                    return '<div style="padding:8px;">' +
                        escapeHtml(value) + '</div>';
                }
            },
            {
                text: 'DOP',
                datafield: 'date',
                width: 155,
                cellsalign: 'center',
                align: 'center',
                cellsrenderer: function (row, column, value) {
                    return '<div style="padding:8px;">' +
                        renderPurchaseDate(value) + '</div>';
                }
            },
            {
                text: 'Cost',
                datafield: 'cost',
                width: 140,
                cellsalign: 'right',
                align: 'center',
                cellsrenderer: function (row, column, value) {
                    return '<div style="padding:8px;">₹' +
                        formatAmount(value, 2) + '</div>';
                }
            },
            {
                text: 'Qty',
                datafield: 'quantity',
                width: 100,
                cellsalign: 'center',
                align: 'center',
                cellsrenderer: function (row, column, value) {
                    return '<div style="padding:8px;">' +
                        formatAmount(value, 0) + 'g</div>';
                }
            },
            {
                text: 'Per Unit',
                datafield: 'perUnit',
                width: 120,
                cellsalign: 'right',
                align: 'center',
                cellsrenderer: function (row, column, value) {
                    return '<div style="padding:8px;">₹' +
                        formatAmount(value, 0) + '</div>';
                }
            },
            {
                text: 'Profit',
                datafield: 'profit',
                width: 140,
                cellsalign: 'right',
                align: 'center',
                cellsrenderer: function (row, column, value) {
                    const profitClass =
                        Number(value) >= 0 ? 'profit' : 'loss';

                    return '<div style="padding:8px;">' +
                        '<span class="' + profitClass + '">' +
                        '₹' + formatAmount(value, 2) +
                        '</span></div>';
                }
            },
            {
                text: 'Search',
                datafield: 'searchText',
                hidden: true
            }
        ]
    });

    return grid;
}


const purchaseGrids = {
    table22: initializePurchaseGrid(
        'table22',
        {{ \Illuminate\Support\Js::from($makePurchaseRows($studentsKind22)) }}
    ),

    table24: initializePurchaseGrid(
        'table24',
        {{ \Illuminate\Support\Js::from($makePurchaseRows($studentsKind24)) }}
    ),

    tableSilver: initializePurchaseGrid(
        'tableSilver',
        {{ \Illuminate\Support\Js::from($makePurchaseRows($studentsKindSS)) }}
    )
};

document.querySelectorAll('.table-search').forEach(function (input) {

    input.addEventListener('input', function () {

        const grid = purchaseGrids[this.dataset.grid];
        const search = this.value.trim();

        if (!grid) return;

        // Clear previous filters
        grid.jqxGrid('clearfilters');

        if (search) {

            const filterGroup = new $.jqx.filter();

            const filter = filterGroup.createfilter(
                'stringfilter',
                search,
                'CONTAINS'
            );

            filterGroup.addFilter(1, filter);

            grid.jqxGrid(
                'addfilter',
                'searchText',
                filterGroup
            );

            grid.jqxGrid('applyfilters');
        }
    });
});
</script>


</body>
</html>
