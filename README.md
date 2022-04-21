# SignedQRGenerator
A PHP server side utilty to create a digitally signed QR code. Digitally signed QR data helps to prevent forgery.

Steps:
1. Fetch private key from keystore
2. Use the private key to generate digital signature of a given input
3. Pack the given input along with it's digtal signature
4. Generate a QR code using the above
5. This QR code is now not only a data, but is a tamper detectable code.
6. Reading the QR data and detection of QR tamper can be done using the special companion Android App.


