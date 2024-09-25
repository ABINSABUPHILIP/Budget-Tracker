# Budget Tracker

A simple web-based application that helps users manage and track their monthly income, expenses, and budget. This tool allows you to add transactions, view your current financial status, and delete unwanted transactions.

## Features
- **Add Income/Expenses**: Log your income and expenses along with a description and note.
- **View Transactions**: See a list of all your transactions.
- **Delete Transactions**: Remove transactions from the list.
- **Separate Monthly Income and Budget**: Track your monthly income and set monthly budget limits.
- **Notes**: Add additional notes to each transaction.
  
## Tech Stack
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: SQLite

## Installation

### Prerequisites
- PHP (>= 7.0)
- XAMPP, WAMP, or any other local server environment.
- SQLite or MySQL for the database.

### Steps to Run

1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-username/budget-tracker.git
   cd budget-tracker
   ```

2. **Set Up Database**
   - If using SQLite:
     - Run the SQL commands from `setup.sql` to create the database and `transactions` table.
     - Place the `budget_tracker.sqlite` file in the project root directory.
   - If using MySQL:
     - Import `setup.sql` into your MySQL database.
     - Configure the database connection in `config.php`.

3. **Run the Application**
   - Start your local server (e.g., using XAMPP or WAMP).
   - Place the project folder in the server's `htdocs` directory.
   - Open a browser and navigate to `http://localhost/budget-tracker`.

### Database Setup Example

```sql
CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TEXT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    date TEXT NOT NULL,
    note TEXT
);
```

## File Structure

```bash
budget-tracker/
├── css/
│   └── style.css     # Contains the styling for the application
├── index.php         # Main entry point for the application
├── config.php        # Database configuration file
├── setup.sql         # SQL file for database setup
├── budget_tracker.sqlite # SQLite database file (optional, if using SQLite)
├── README.md         # Project documentation
```

## How to Use

1. **Add Income/Expenses**: Use the form at the top of the page to input the amount, type (income/expense), date, and note.
2. **View Transactions**: The transaction list will be displayed below the form, showing all logged entries.
3. **Delete Transactions**: Use the trash icon beside each transaction to delete it.

## Contributing

If you would like to contribute to this project, feel free to fork the repository and submit a pull request.

---

### Note:
Replace the placeholder in the GitHub URL (`your-username`) with your actual GitHub username when you upload it to your repository.# Budget-Tracker
