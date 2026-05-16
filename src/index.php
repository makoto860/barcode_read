<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Book Scanner</title>

    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        #reader {
            width: 300px;
            margin-bottom: 20px;
        }

        #book-list {
            margin-top: 20px;
        }

        .book-item {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>

<h1>本バーコード読み取り</h1>

<div id="reader"></div>

<h2>読み取り一覧</h2>

<div id="book-list"></div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

const bookList = document.getElementById('book-list');

function addBook(title, author) {

    const div = document.createElement('div');

    div.className = 'book-item';

    div.innerHTML = `
        <strong>${title}</strong><br>
        ${author}
    `;

    bookList.prepend(div);
}

function onScanSuccess(decodedText) {

    const isbn = decodedText.replace(/[^0-9]/g, '');

    console.log("RAW:", decodedText);
    console.log("ISBN:", isbn);

    if (!isISBN(isbn)) {
        console.log("ISBNではないため無視:", isbn);
        return;
    }

    fetch("book.php?isbn=" + isbn)
        .then(res => res.json())
        .then(data => {

            if (!data || !data[0]) {
                alert("本が見つかりません");
                return;
            }

            const summary = data[0].summary;

            addBook(summary.title, summary.author);
        });
}

const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: 250,
        formatsToSupport: [
            Html5QrcodeSupportedFormats.EAN_13
        ]
    }
);

function isISBN(code) {
    return /^978\d{10}$/.test(code);
}

html5QrcodeScanner.render(onScanSuccess);

</script>

</body>
</html>
