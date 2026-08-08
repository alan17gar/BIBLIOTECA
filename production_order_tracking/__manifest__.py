{
    'name': 'Production Order Tracking',
    'version': '18.0.1.0.0',
    'summary': 'Module to track production orders, daily production, and lots.',
    'description': """
        This module allows tracking of production orders, including:
        - Registering purchase orders with items and quantities in kg.
        - Capturing daily production linked to an order.
        - Automatically calculating the remaining quantity to meet deadlines.
        - Generating a weekly report.
        - Assigning each production to a lot.
    """,
    'author': 'Jules',
    'website': 'https://github.com/odoo/odoo',
    'category': 'Production',
    'depends': ['base', 'report_xlsx'],
    'data': [
        'security/security.xml',
        'security/ir.model.access.csv',
        'data/sequence.xml',
        'wizards/production_daily_wizard_views.xml',
        'wizards/weekly_report_wizard_views.xml',
        'views/production_order_views.xml',
        'views/production_daily_views.xml',
        'views/production_lot_views.xml',
        'views/menu.xml',
        'report/weekly_report_templates.xml',
        'report/report_actions.xml',
    ],
    'installable': True,
    'application': True,
    'auto_install': False,
}
