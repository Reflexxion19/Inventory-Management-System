let html5QrcodeScanner1, html5QrcodeScanner2;
var check = false;
var check1 = false;

function onScanSuccess1(decodedText, decodedResult) {
    document.getElementById(input1).value = decodedText;
}

function onScanSuccess2(decodedText, decodedResult) {
    document.getElementById(input2).value = decodedText;
}

function onScanFailure1(error) {}

function onScanFailure2(error) {}

storage_tab = document.getElementById(tab1);
loan_tab = document.getElementById(tab2);

storage_tab.addEventListener("click", storageTabFunction);
loan_tab.addEventListener("click", loanTabFunction);

function storageTabFunction() {
    if (check1) {
        html5QrcodeScanner2.clear();
    }

    html5QrcodeScanner1 = new Html5QrcodeScanner(
        reader1,
        { fps: 20, qrbox: {width: 250, height: 250} },
        false
    );
    html5QrcodeScanner1.render(onScanSuccess1, onScanFailure1);

    check = true;
}

function loanTabFunction() {
    if (check) {
        html5QrcodeScanner1.clear();
    }

    html5QrcodeScanner2 = new Html5QrcodeScanner(
        reader2,
        { fps: 20, qrbox: {width: 250, height: 250} },
        false
    );
    html5QrcodeScanner2.render(onScanSuccess2, onScanFailure2);

    check1 = true;
}

if(!check){
    html5QrcodeScanner1 = new Html5QrcodeScanner(
        reader1,
        { fps: 20, qrbox: {width: 250, height: 250} },
        false
    );
    html5QrcodeScanner1.render(onScanSuccess1, onScanFailure1);

    check = true;
}