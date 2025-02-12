<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - SIM Global UNAIR</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            max-width: 80%;
            height: auto;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #24695c;
            text-decoration: none;
            border-radius: 5px;
            /* margin-top: 20px; */
            cursor: pointer;
        }
        .button:hover {
            background-color: #1b4f44;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        @media screen and (max-width: 480px) {
            .container {
                padding: 15px;
            }
            .button {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{asset('assets/images/logo/unair_age.png')}}" alt="UNAIR Logo" class="logo">
        <h1>Email Verification - SIM Global UNAIR</h1>
        <hr>
        <p>To verify your email address, please click the button below:</p>
        <a href="#" onclick="handleVerification(event)" class="button">Verify Email</a>
        <p>If you did not request this email, please ignore it or alert us immediately.</p>
        <p class="footer">Thanks,<br>Airlangga Global Engagement</p>
    </div>

<script>
   function handleVerification(event) {
    event.preventDefault();

    const encryptedUsername = '{{ $encryptedUsername }}'; 
    const encryptedEmail = '{{ $encryptedEmail }}'; 

    fetch(`/email-verify/${encryptedUsername}/${encryptedEmail}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Verification Success',
                text: data.message,
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload(); 
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Verification Failed',
                text: data.message || 'Please try again.',
                confirmButtonText: 'Try Again'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong. Please try again.',
            confirmButtonText: 'OK'
        });
    });
}
</script>

</body>
</html>
