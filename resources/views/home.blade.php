<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel CQRS</title>

        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <style>
            body {
                overflow-y: scroll;
                min-height: 100%;
            }
            html, body {
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                font-size: 15px;
                margin: 0;
            }
            input {
                font-family: 'Nunito', sans-serif;
                font-weight: 600;
                font-size: 13px;
                margin-left: 10px;
                margin-right: 10px;
                padding-left: 5px;
                padding-bottom: 3px;
                padding-top: 3px;
            }
            .container {
                flex: 1;
                display: flex;
                flex-direction: row;
                min-height: 100vh;
            }
            .txn-form-container {
                padding: 20px;
                width: 50%;
                min-height: 100%;
            }
            .txn-form-container > div:first-child {
                margin-top: 0;
            }
            .txn-form-group {
                display: flex;
                flex-direction: row;
                align-items: center;
                margin-top: 20px;
            }
            .txn-form-label-group {
                min-width: 150px;
            }
            .txn-list-container {
                border: solid 1px #ccc;
                padding: 18px;
                width: 50%;
                min-height: 100%;
            }
            .txn-list-container-header {
                text-align: center;
            }

            .list {
                list-style: none;
            }
            .list-view {
                margin-top: 15px;
                margin-bottom: 5px;
            }
        </style>

        <script src="/fetch-polyfill.js"></script>

        <script>
            function init() {
                //transactionsQuery();
                renderTransactions();
            }

            function sendMoney() {
                try {
                    const fromEmail = document.querySelector('[data-form="txn-form-send-money-main"] [name="from-email"]').value;
                    const toEmail = document.querySelector('[data-form="txn-form-send-money-main"] [name="to-email"]').value;
                    const amount = document.querySelector('[data-form="txn-form-send-money-main"] [name="amount"]').value;
                    //
                    const formData = new FormData();
                    formData.append('from_email', fromEmail);
                    formData.append('to_email', toEmail);
                    formData.append('amount', amount);
                    formData.append('currency', 'GBP');
                    //
                    fetch('/api/users/commands/send-money', {
                        method: 'POST',
                        body: formData,
                    });

                    location.reload();

                } catch (err) {
                    console.error(err);
                    confirm('Sorry, something went wrong. Please try again later');
                }
            }

            async function transactionsQuery() {
                try {
                    let response = await fetch('/api/users/queries/transactions', {
                        method: 'GET',
                    });
                    return await response.json();
                }  catch (error) {
                    console.error(error);
                }
            }

            async function renderTransactions() {
                try {
                    let transactions = await transactionsQuery();
                    let html = '';
                    transactions = transactions.txns;

                    transactions.forEach(transaction => {
                        let htmlSegment = `<div class="list-view">
                            <li class="list"><b>Transaction ID: </b>${transaction.id}</li>
                            <li class="list"><b>Sender: </b>${transaction.from_email}</li>
                            <li class="list"><b>Recipient: </b>${transaction.to_email}</li>
                            <li class="list"><b>Amount: </b>${transaction.amount}</li>
                            <li class="list"><b>Currency: </b>${transaction.currency}</li>
                            <li class="list"><b>Sended date: </b>${transaction.created_at}</li>
                        </div>`;
                        html += htmlSegment;
                    });

                    let transactionsList = document.querySelector('.transactionsList');
                    transactionsList.innerHTML = html;
                } catch (error) {
                    console.log(error);
                    let transactionsList = document.querySelector('.transactionsList');
                    transactionsList.innerHTML = 'Something wrong. Try later.';
                }
            }

            init();
        </script>
    </head>
    <body>
        <form
            data-form="txn-form-send-money-main"
            onsubmit="typeof event !== 'undefined' && event.preventDefault(); sendMoney(); return false;"
            action="/api/users/commands/send-money"
            method="POST"
            enctype="multipart/form-data"
        >
            <div class="container">
                <div class="txn-form-container">
                    <div class="txn-form-group">
                        <div class="txn-form-label-group">Your Email</div> <input name="from-email" placeholder="Your Email" />
                    </div>
                    <div class="txn-form-group">
                        <div class="txn-form-label-group">Send Money To</div> <input name="to-email" placeholder="Recipient Email" />
                    </div>
                    <div class="txn-form-group">
                        <div class="txn-form-label-group">Send Amount</div>
                        <input name="amount" placeholder="Amount" />
                        <span style="font-weight: bold;">GBP</span>
                    </div>
                    <div class="txn-form-group">
                        <button type="submit">SEND</button>
                    </div>
                </div>
                <div class="txn-list-container">
                    <div class="txn-list-container-header">TXN List Container</div>
                    <div class="transactionsList"></div>
                </div>
            </div>
        </form>
    </body>
</html>
