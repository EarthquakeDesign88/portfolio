<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf')}}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf')}}") format('truetype');
        }


        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew Italic.ttf')}}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf')}}") format('truetype');
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'THSarabunNew';
            font-size: 30px;
            margin: 5px;
            text-align: center;
        }

        tr {
            text-align: center;
        }
    </style>
</head>

<body>
    <table>
        <tbody>
            @php
                $i = 0;
            @endphp
            
            @foreach($data as $location)
            
            @if($i % 3 == 0 || $i == 0)
            <tr>
            @endif

                <td>
                    <div style="width: 180px;height: 200px;border: 1px solid #000;text-align:center; padding: 15px; margin: 5px">
                        <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('storage/qr_codes/qr_' . $location->location_qr . '.svg'))) }}" alt="QR Code">
                        <h4>{{$location->zone_description}}_{{ $location->location_description}}</h4>
                    </div>
                </td>

            @if($i % 2 == 0 && $i != 0)
            </tr>
            @endif

            @php
                $i++;
                if($i > 2){
                    $i = 0;
                }
            @endphp

            @endforeach
        </tbody>
    </table>
</body>