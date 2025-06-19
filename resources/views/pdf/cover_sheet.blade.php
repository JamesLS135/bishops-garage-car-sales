<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cover Sheet - {{ $car->registration_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .container { width: 100%; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header h2 { margin: 0; font-weight: normal; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table th, .details-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .details-table th { background-color: #f2f2f2; width: 150px; }
        .qr-code { position: absolute; top: 10px; right: 10px; }
    </style>
</head>
<body>
    <div class="qr-code">
        {!! $qrCode !!}
    </div>

    <div class="container">
        <div class="header">
            <h1>Bishop's Garage</h1>
            <h2>Car Sales Checklist</h2>
            <hr>
        </div>

        <table class="details-table">
            <tr>
                <th>System ID</th>
                <td>{{ $car->id }}</td>
            </tr>
            <tr>
                <th>Registration</th>
                <td><strong>{{ $car->registration_number }}</strong></td>
            </tr>
            <tr>
                <th>Make & Model</th>
                <td>{{ $car->make }} {{ $car->model }}</td>
            </tr>
            <tr>
                <th>Year</th>
                <td>{{ $car->year }}</td>
            </tr>
            <tr>
                <th>VIN</th>
                <td>{{ $car->vin }}</td>
            </tr>
            <tr>
                <th>Colour</th>
                <td>{{ $car->color ?? 'N/A' }}</td>
            </tr>
             <tr>
                <th>Mileage (Current)</th>
                <td>{{ number_format($car->odometer_reading) }}</td>
            </tr>
             <tr>
                <th>Fuel Type</th>
                <td>{{ $car->fuel_type ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Engine Size</th>
                <td>{{ $car->engine_size ?? 'N/A' }}</td>
            </tr>
        </table>

        <br>

        <table class="details-table">
             <tr>
                <th>Purchase Price</th>
                <td>€{{ number_format($car->purchase->purchase_price ?? 0, 2) }}</td>
            </tr>
            <tr>
                <th>PX Value</th>
                <td>€{{ number_format($car->sale->part_exchange_value ?? 0, 2) }}</td>
            </tr>
            <tr>
                <th>Total Expenses</th>
                <td>€{{ number_format($car->total_cost - ($car->purchase->purchase_price ?? 0), 2) }}</td>
            </tr>
            <tr>
                <th>Sale Price</th>
                <td>€{{ number_format($car->sale->selling_price ?? 0, 2) }}</td>
            </tr>
        </table>
        
        <br>

        <table class="details-table">
            <tr>
                <th>Keys</th>
                <td>{{ $car->number_of_keys_present ?? 'N/A' }}</td>
            </tr>
             <tr>
                <th>Tax Book</th>
                <td>{{ $car->tax_book_status ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>NCT Status</th>
                 <td>{{ $car->purchase->nct_status ?? 'N/A' }}</td>
            </tr>
             <tr>
                <th>Service History</th>
                <td>{{ $car->workDone->count() > 0 ? 'Present' : 'None Recorded' }}</td>
            </tr>
            <tr>
                <th>Validated</th>
                <td>{{ $car->validated_status ?? 'N/A' }}</td>
            </tr>
             <tr>
                <th>Window Sticker</th>
                 <td>{{ $car->window_sticker_present ? 'Yes' : 'No' }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
