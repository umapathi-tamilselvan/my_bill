<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $bill->billNumber }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .invoice-info-left, .invoice-info-right {
            display: table-cell;
            width: 50%;
        }
        .invoice-info-right {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .summary {
            margin-top: 20px;
        }
        .summary table {
            width: 300px;
            margin-left: auto;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p>Store Billing System</p>
    </div>

    <div class="invoice-info">
        <div class="invoice-info-left">
            <strong>Bill To:</strong><br>
            @if($bill->customerId && isset($bill->customer))
                {{ $bill->customer->name }}<br>
                @if($bill->customer->address)
                    {{ $bill->customer->address }}<br>
                @endif
                @if($bill->customer->phone)
                    Phone: {{ $bill->customer->phone }}<br>
                @endif
            @else
                Walk-in Customer
            @endif
        </div>
        <div class="invoice-info-right">
            <strong>Invoice #:</strong> {{ $bill->billNumber }}<br>
            <strong>Date:</strong> {{ $bill->billDate }}<br>
            <strong>Status:</strong> {{ ucfirst($bill->paymentStatus) }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Tax</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bill->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->productName }}<br><small>({{ $item->productCode }})</small></td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unitPrice, 2) }}</td>
                    <td class="text-right">{{ number_format($item->taxAmount, 2) }}</td>
                    <td class="text-right">{{ number_format($item->discountAmount, 2) }}</td>
                    <td class="text-right">{{ number_format($item->totalAmount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <th>Subtotal:</th>
                <td class="text-right">{{ number_format($bill->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th>Tax:</th>
                <td class="text-right">{{ number_format($bill->taxAmount, 2) }}</td>
            </tr>
            <tr>
                <th>Discount:</th>
                <td class="text-right">{{ number_format($bill->discountAmount, 2) }}</td>
            </tr>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <th>Total:</th>
                <td class="text-right">{{ number_format($bill->totalAmount, 2) }}</td>
            </tr>
            <tr>
                <th>Paid:</th>
                <td class="text-right">{{ number_format($bill->paidAmount, 2) }}</td>
            </tr>
            <tr>
                <th>Balance:</th>
                <td class="text-right">{{ number_format($bill->totalAmount - $bill->paidAmount, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($bill->notes)
        <div style="margin-top: 20px;">
            <strong>Notes:</strong> {{ $bill->notes }}
        </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated invoice.</p>
    </div>
</body>
</html>

