# Production Order Tracking

Odoo 18.0 module to track production orders, daily production, and lots.

## Features

- Register purchase orders with items and quantities in kg.
- Capture daily production linked to an order.
- Automatically calculate the remaining quantity to meet deadlines.
- Generate a weekly report.
- Assign each production to a lot.
- Visual warnings for overdue orders.
- User-friendly interface with smart buttons and clear actions.
- Role-based security (production user and manager).

## Installation

1.  Clone this repository or download the source code.
2.  Copy the `production_order_tracking` directory to your Odoo `addons` path.
3.  Restart the Odoo server.
4.  Go to `Apps` in your Odoo instance.
5.  Click on `Update Apps List`.
6.  Search for "Production Order Tracking" and click `Install`.

## Configuration

No special configuration is required after installation.

## Usage

### Production Orders

-   Go to `Production > Production Orders` to create and manage production orders.
-   Fill in the product name, total quantity in kg, and the deadline.
-   Confirm the order to move it to the "In Progress" state.

### Registering Daily Production

-   From a production order in the "In Progress" state, click on "Register Daily Production".
-   A wizard will appear where you can enter the produced quantity for the day and assign it to a lot.
-   You can either select an existing lot or create a new one directly from the wizard.

### Managing Lots

-   Go to `Production > Production Lots` to view and manage production lots.
-   You can see the total quantity produced for each lot and the daily production entries associated with it.

### Generating Reports

-   Go to `Production > Production Orders` and select a production order.
-   Click on "Generate Weekly Report" to open the report wizard.
-   Select the date range and click on "Print PDF" or "Print XLSX" to generate the report.

## Security

-   **Production User**: Can create and edit their own daily production entries and view orders and lots.
-   **Production Manager**: Can create and manage production orders, generate reports, and administer lots.
