<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Notification</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .header img {
      max-width: 200px;
    }
    .details {
      margin-bottom: 20px;
    }
    .details table {
      width: 100%;
    }
    .details table tr {
      border-bottom: 1px solid #ccc;
    }
    .details table tr:last-child {
      border-bottom: none;
    }
    .details table th,
    .details table td {
      padding: 10px;
      text-align: left;
    }
    .footer {
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img class="main-logo" src="{{ logo_url() }}" alt="Company Logo" />
    </div>
    <div class="content">
      <h1>Payment Notification</h1>
      <div class="details">
        <table>
          <tr>
            <th>Name:</th>
            <td>{{ $paymentData['name'] }}</td>
          </tr>
          <tr>
            <th>Email:</th>
            <td>{{ $paymentData['email'] }}</td>
          </tr>
          <tr>
            <th>Amount:</th>
            <td>{{ $paymentData['amount'] }} {{ $paymentData['currency'] }}</td>
          </tr>
          <tr>
            <th>Payment Method:</th>
            <td>{{ $paymentData['payment_method'] }}</td>
          </tr>         
          <tr>
            <th>Account Number:</th>
            <td>{{ $paymentData['account_number'] }}</td>
          </tr>
          <tr>
            <th>Branch Name:</th>
            <td>{{ $paymentData['branch_name'] }}</td>
          </tr>
         
        </table>
      </div>
      <div class="footer">
        <p>Thank you for your support!</p>
      </div>
    </div>
  </div>
</body>
</html>
