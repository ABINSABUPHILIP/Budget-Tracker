window.onload = function() {
    fetchTransactions();
};

function fetchTransactions() {
    fetch('index.php')
        .then(response => response.json())
        .then(data => {
            const transactionList = document.getElementById('transactionList');
            transactionList.innerHTML = '';
            data.forEach(transaction => {
                const li = document.createElement('li');
                li.textContent = `${transaction.date} - ${transaction.type}: $${transaction.amount}`;
                transactionList.appendChild(li);
            });
        });
}
