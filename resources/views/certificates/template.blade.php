<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            /* width: 297mm; */
            /* height: 110mm; */
            margin: 0;
            padding: 0;
            background: white;
        }

        body {
            padding: 12mm;
            position: relative;
        }

        .certificate-container {
            width: 100%;
            height: 90%;
            border: 4px solid #1F2937;
            border-radius: 8px;
            position: relative;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .certificate-inner {
            padding: 30px 60px 25px 60px;
            /* height: 100%; */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .qr-code {
            width: 22mm;
            height: 22mm;
            text-align: center;
        }

        .qr-code img {
            width: 22mm;
            height: 22mm;
            margin-bottom: 3px;
        }

        .qr-code-label {
            font-size: 5.5pt;
            color: #6B7280;
            font-weight: 500;
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .certificate-title {
            font-size: 30pt;
            font-weight: 700;
            color: #1F2937;
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .title-underline {
            width: 85px;
            height: 3.5px;
            background: linear-gradient(to right, #FBBF24, #F59E0B);
            margin: 0 auto;
            border-radius: 2px;
        }

        .certificate-body {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* max-width: 700px; */
            padding: 5px 0;
        }

        .certify-text {
            font-size: 12pt;
            color: #6B7280;
            margin-bottom: 15px;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        .participant-name {
            font-size: 24pt;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 10px;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }

        .name-underline {
            width: 280px;
            height: 2px;
            background: #D1D5DB;
            margin: 0 auto 25px auto;
        }

        .completion-text {
            font-size: 12pt;
            color: #6B7280;
            margin-bottom: 15px;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        .event-title {
            font-size: 17pt;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 15px;
            letter-spacing: -0.2px;
            line-height: 1.3;
        }

        .event-date {
            font-size: 11pt;
            color: #6B7280;
            font-weight: 400;
            margin-bottom: 0;
        }

        .footer-section {
            width: 100%;
            padding: 0 10px;
            margin-top: 20px;
        }

        .certificate-number {
            font-size: 9pt;
            color: #9CA3AF;
            text-align: center;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
            font-weight: 400;
        }

        .signatories {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 40px;
            flex-wrap: nowrap;
            margin-bottom: 20px;
            margin-top: 0;
            width: 100%;
            padding: 0;
        }

        /* Dynamic signatory sizing based on count */
        .signatories.count-1 {
            justify-content: center;
        }

        .signatories.count-2 {
            justify-content: space-between;
            padding: 0 60px;
        }

        .signatories.count-3 {
            justify-content: space-around;
        }

        .signatories.count-4 {
            justify-content: space-around;
        }

        .signatory {
            text-align: center;
            flex: 0 0 auto;
            min-width: 128px;
        }

        .signature-line {
            width: 128px;
            height: 0;
            border-top: 1px solid #9CA3AF;
            margin: 0 auto 8px auto;
        }

        .signatory-name {
            font-size: 9pt;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 2px;
            letter-spacing: 0.1px;
        }

        .signatory-title {
            font-size: 7.5pt;
            color: #4B5563;
            font-weight: 400;
            line-height: 1.2;
        }

        .blockchain-info {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            gap: 15px;
            font-size: 6.5pt;
            color: #9CA3AF;
            line-height: 1.5;
            text-align: left;
        }

        .blockchain-details {
            flex: 0 1 auto;
            max-width: 600px;
        }

        .blockchain-row {
            margin-bottom: 2px;
            text-align: left;
        }

        .blockchain-left {
            display: block;
        }

        .blockchain-right {
            display: block;
            font-weight: 600;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="certificate-inner">
            <div class="certificate-header">
                <div class="certificate-title">Certificate of Completion</div>
                <div class="title-underline"></div>
            </div>

            <div class="certificate-body">
                <div class="certify-text">This is to certify that</div>

                <div class="participant-name">{{ $certificate->participant_name }}</div>
                <div class="name-underline"></div>

                <div class="completion-text">has successfully completed</div>

                <div class="event-title">{{ $certificate->event_title }}</div>

                <div class="event-date">on {{ $certificate->completion_date->format('F j, Y') }}</div>
            </div>

            <div class="footer-section">
                <div class="certificate-number">Certificate #{{ $certificate->certificate_number }}</div>

                <div class="signatories">
                    @forelse($signatories as $signatory)
                        <div class="signatory">
                            <div class="signature-line"></div>
                            <div class="signatory-name">{{ $signatory['name'] ?? 'Signatory' }}</div>
                            @if (!empty($signatory['title']))
                                <div class="signatory-title">{{ $signatory['title'] }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="signatory">
                            <div class="signature-line"></div>
                            <div class="signatory-name">Authorized Signatory</div>
                            <div class="signatory-title">Position</div>
                        </div>
                    @endforelse
                </div>

                @if ($certificate->certificate_hash)
                    <div class="blockchain-info">
                        <div class="blockchain-details">
                            <div class="blockchain-row">
                                <div class="blockchain-left">
                                    Hash: {{ substr($certificate->certificate_hash, 0, 35) }}...
                                </div>
                                @if ($certificate->is_blockchain_verified)
                                    <div class="blockchain-right">
                                        Verified on Polygon Amoy
                                    </div>
                                @endif
                            </div>
                            @if ($certificate->blockchain_tx_hash)
                                <div class="blockchain-row">
                                    <div class="blockchain-left">
                                        TX: {{ substr($certificate->blockchain_tx_hash, 0, 45) }}...
                                    </div>
                                </div>
                            @endif
                            @if ($certificate->blockchain_issued_at)
                                <div class="blockchain-row">
                                    Issued: {{ $certificate->blockchain_issued_at->format('M j, Y g:i A') }}
                                </div>
                            @endif
                        </div>
                        @if ($qrCodeBase64)
                            <div class="qr-code">
                                <img src="{{ $qrCodeBase64 }}" alt="QR Code">
                                <div class="qr-code-label">Scan to Verify</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
