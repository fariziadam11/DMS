<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Dapentelkom DMS')</title>
    <style>
        /* Reset styles */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 30px 20px;
            text-align: center;
        }

        .logo-container {
            margin-bottom: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 8px;
        }

        .header-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .header-subtitle {
            color: #e0e7ff;
            font-size: 14px;
            margin: 5px 0 0 0;
        }

        /* Content */
        .email-content {
            padding: 40px 30px;
            color: #334155;
            line-height: 1.6;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .message-text {
            font-size: 15px;
            color: #475569;
            margin-bottom: 15px;
        }

        /* Button */
        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .button:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 6px 8px rgba(59, 130, 246, 0.4);
        }

        .button-secondary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        }

        .button-secondary:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .button-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3);
        }

        .button-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        /* Info Box */
        .info-box {
            background-color: #f1f5f9;
            border-left: 4px solid #3b82f6;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .info-box-success {
            background-color: #f0fdf4;
            border-left-color: #10b981;
        }

        .info-box-warning {
            background-color: #fef3c7;
            border-left-color: #f59e0b;
        }

        .info-box-danger {
            background-color: #fef2f2;
            border-left-color: #ef4444;
        }

        .info-label {
            font-weight: 600;
            color: #1e293b;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #475569;
            font-size: 15px;
        }

        /* Footer */
        .email-footer {
            background-color: #f8fafc;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer-text {
            color: #64748b;
            font-size: 13px;
            margin: 5px 0;
        }

        .footer-link {
            color: #3b82f6;
            text-decoration: none;
        }

        .footer-link:hover {
            text-decoration: underline;
        }

        /* Divider */
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 25px 0;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }

            .email-content {
                padding: 30px 20px !important;
            }

            .button {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>

<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color: #f4f7fa;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container">
                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <h1 class="header-title">Dapentelkom</h1>
                            <p class="header-subtitle">Document Management System</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="email-content">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <p class="footer-text">
                                Email ini dikirim secara otomatis oleh sistem Dapentelkom DMS.
                            </p>
                            <p class="footer-text">
                                Jika Anda memiliki pertanyaan, silakan hubungi
                                <a href="mailto:support@dapentelkom.co.id"
                                    class="footer-link">support@dapentelkom.co.id</a>
                            </p>
                            <p class="footer-text" style="margin-top: 15px; color: #94a3b8;">
                                &copy; {{ date('Y') }} Dapentelkom. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
