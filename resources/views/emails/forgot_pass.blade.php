<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account - SIM Global UNAIR</title>
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
        }
        .button:hover {
            background-color: #1b4f44;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        .username, .vercode {
            font-size: 130%;
            font-weight: bold;
            display: block;
            margin: 10px 0;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <img src="{{asset('assets/images/logo/unair_age.png')}}" alt="UNAIR Logo" class="logo">
        <h1>Your Account - SIM Global UNAIR</h1>
        <hr>
        <p><b>Dear {{$nama->nama}},</b></p>
        <p>Your username:</p>
        <span class="username">{{$username}}</span>
        <!-- <p>Please reset your password by clicking the button below:</p> -->
        <!-- <a href="{{route('verify.codebase', ['encryptedUsername'=>$encryptedUsername])}}" class="button">Reset Password</a> -->
        <p>Your verification code:</p>
        @if($ver_code->user_token)
            <span class="vercode">{{$ver_code->user_token}}</span>
        @else
            <span class="vercode"></span>
        @endif
        <b style="color:red">This verification code is confidential. Please do not share it with anyone!</b>
        <p>If you did not request this email, please ignore it or alert us immediately.</p>
        <p class="footer">Thanks,<br>Airlangga Global Engagement</p>
    </div>
</body>
</html>
