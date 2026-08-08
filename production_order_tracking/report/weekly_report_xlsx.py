from odoo import models

class WeeklyProductionXLSX(models.AbstractModel):
    _name = 'report.production_order_tracking.weekly_production_xlsx'
    _inherit = 'report.report_xlsx.abstract'
    _description = 'Weekly Production XLSX Report'

    def generate_xlsx_report(self, workbook, data, orders):
        report_model = self.env['report.production_order_tracking.report_weekly_production_document']
        report_data = report_model._get_report_values(orders.ids, {'data': data})

        sheet = workbook.add_worksheet('Weekly Production Report')
        bold = workbook.add_format({'bold': True})

        # Headers
        headers = [
            'Order', 'Product', 'Total Qty (kg)', 'Produced This Week (kg)',
            'Total Produced (kg)', 'Remaining (kg)', 'Deadline', 'Status'
        ]
        for i, header in enumerate(headers):
            sheet.write(0, i, header, bold)

        # Data
        row = 1
        for order in report_data['docs']:
            weekly_production = report_data['get_weekly_production'](order, report_data['data'])
            sheet.write(row, 0, order.name)
            sheet.write(row, 1, order.product_name)
            sheet.write(row, 2, order.quantity_kg)
            sheet.write(row, 3, weekly_production)
            sheet.write(row, 4, order.produced_kg)
            sheet.write(row, 5, order.remaining_kg)
            sheet.write(row, 6, order.deadline_date.strftime('%Y-%m-%d'))
            sheet.write(row, 7, order.state)
            row += 1
