document.addEventListener('DOMContentLoaded', function() {
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    const qrScanned = document.getElementById('qr_scanned');

    scanner.addListener('scan', function(content) {
        console.log(content);

        const qrDataElement = document.getElementById('qr_data');

        qrDataElement.value = content;
        qrScanned.classList.add('qr-scanned');

        // Send the scanned QR data to the API
        sendQRDataToAPI(content);
    });

    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            console.error('No cameras found.');
        }
    }).catch(function(e) {
        console.error(e);
    });
});

function handleSuccess(message) {
    const responseMessageElement = document.getElementById('response-message');
    responseMessageElement.textContent = message;
    responseMessageElement.classList.remove('error-message');
    responseMessageElement.classList.add('success-message');

    setTimeout(function() {
        qrScanned.classList.remove('qr-scanned');
    }, 3000);
}

function handleError(message) {
    const responseMessageElement = document.getElementById('response-message');
    responseMessageElement.textContent = message;
    responseMessageElement.classList.add('error-message');

    setTimeout(function() {
        qrScanned.classList.remove('qr-scanned');
    }, 3000);
}

function sendQRDataToAPI(qrData) {
    // Prepare the request data as a JSON object
    const requestData = {
        qrData: qrData
    };

    const responseMessageElement = document.getElementById('response-message');

    // Make a POST request to the API
    fetch('api/qr_scanner.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json()) 
    .then(result => {
        // console.log(result); 
        
        var message = result.message;

        if (result.status === "success") {
            handleSuccess(message);
        } else {
            handleError(message);
        }
    })
    .catch(error => {
        console.error('Error sending QR data to API:', error);
    });
}
