@component('mail::message')
# Payment Notification

Thank you for your payment! Below are the details:

**Name:** {{ $paymentData['name'] }}
**Email:** {{ $paymentData['email'] }}
**Amount:** {{ $paymentData['amount'] }} {{ $paymentData['currency'] }}
**Payment Method:** {{ $paymentData['payment_method'] }}

**Bank Swift Code:** {{ $paymentData['bank_swift_code'] }}
**Account Number:** {{ $paymentData['account_number'] }}
**Branch Name:** {{ $paymentData['branch_name'] }}
**Branch Address:** {{ $paymentData['branch_address'] }}
**Account Name:** {{ $paymentData['account_name'] }}
**IBAN:** {{ $paymentData['iban'] }}

Thank you for your support!

@endcomponent
