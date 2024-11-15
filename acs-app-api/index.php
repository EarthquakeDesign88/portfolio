<html>
    <head>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="./style.css">
    </head>
    <body>
        <div class="container" style="margin-top: 15px;">
            <div class="row">
                <div class="col-md-6" id="qr_scanned">
                    <video id="preview" width="100%"></video>
                </div>
                <div class="col-md-6">
                    <label style="font-size: 28px">SCAN QR CODE</label>
                    <input type="text" name="text" id="qr_data" readonly="" placeholder="QR Data" class="form-control">

                    
                    <h3 id="response-message"></h3>
                </div>
            </div>

        </div>

        <script type="text/javascript" src="script.js"></script>
    </body>
</html>