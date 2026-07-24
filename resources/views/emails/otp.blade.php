<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h2 style="color: #333333; text-align: center;">Reset Password</h2>
        <p style="color: #666666; font-size: 16px; line-height: 1.5;">Anda menerima email ini karena ada permintaan reset password untuk akun Anda. Silakan gunakan kode OTP di bawah ini untuk melanjutkan:</p>
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #4f46e5; background-color: #f3f4f6; padding: 15px 30px; border-radius: 8px; display: inline-block;">{{ $otp }}</span>
        </div>
        <p style="color: #999999; font-size: 14px; text-align: center;">Kode OTP ini hanya berlaku selama 10 menit.</p>
        <hr style="border: 0; border-top: 1px solid #eeeeee; margin: 30px 0;">
        <p style="color: #999999; font-size: 12px; text-align: center;">Jika Anda tidak meminta reset password, Anda dapat mengabaikan email ini.</p>
    </div>
</body>
</html>
