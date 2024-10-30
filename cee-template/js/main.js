(function () {
    'use strict';

    var finderResults = false;
    var scanning = false;
    var scanButton;
    var spinnerElement;

	var config = {token: cee_token};
	
    var cloudRecognition = new craftar.CloudRecognition(config);

    cloudRecognition.addListener('results', function (err, response, xhr) {
        if (response.results && response.results.length > 0) {
            finderResults = true;
            cloudRecognition.stopFinder();
            scanButton.innerHTML = cee_startScan;
            scanning = false;
            let responseItem = response.results[0];
            window.location = responseItem.item.url;
        }
    });

    cloudRecognition.addListener('finderFinished', function () {
        var spinnerElement = document.getElementById('spinner');
        spinnerElement.setAttribute("class", "spinner hidden");
        scanButton.innerHTML = cee_startScan;
        scanning = false;
        if (!finderResults) {
            alert(cee_noResults);
        }
    });

    function init() {
        scanButton = document.querySelector('#scan');
        spinnerElement = document.getElementById('spinner');

        if (craftar.supportsCapture()) {
            setupCapture(function (err, captureObject) {
                if (err) {
                    alert('there was an error initilizating the camera ( no device present? )' + err)
                } else {
                    var captureDivElement = document.getElementById('videoCapture');
                    captureDivElement.appendChild(captureObject.domElement);

                    scanButton.addEventListener('click', function () {
                        scanning = !scanning;
                        if (scanning) {
                            scanButton.innerHTML = cee_stopScan;
                            cloudRecognition.startFinder(captureObject, 2000, 3);
                            spinnerElement.setAttribute("class", "spinner");
                        } else {
                            scanButton.innerHTML = cee_startScan;
                            cloudRecognition.stopFinder();
                            spinnerElement.setAttribute("class", "spinner hidden");
                        }
                    });
                }
            });
        } else {
            alert("This browser don't support HTML5 features needed for the capture mode");
        }
    };

    window.addEventListener("load", init, false);

    function setupCapture(callback) {
        var capture = new craftar.Capture();

        capture.addListener('started', function () {
            callback(null, capture);
        });

        capture.addListener('error', function (error) {
            callback(error, capture);
        });

        capture.start();
    }
})();
