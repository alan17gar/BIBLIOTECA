from odoo import models, fields, api
from odoo.tools.misc import get_lang

from datetime import timedelta

class WeeklyReportWizard(models.TransientModel):
    _name = 'production.weekly.report.wizard'
    _description = 'Wizard to generate a weekly production report'

    def _default_date_start(self):
        today = fields.Date.today()
        return today - timedelta(days=today.weekday())

    def _default_date_end(self):
        today = fields.Date.today()
        return today + timedelta(days=6 - today.weekday())

    date_start = fields.Date(string='Start Date', required=True, default=_default_date_start)
    date_end = fields.Date(string='End Date', required=True, default=_default_date_end)

    def print_pdf_report(self):
        self.ensure_one()
        data = {'date_start': self.date_start, 'date_end': self.date_end}
        docids = self.env.context.get('active_ids', [])
        return self.env.ref('production_order_tracking.action_report_weekly_production').report_action(docids, data=data)

    def print_xlsx_report(self):
        self.ensure_one()
        data = {'date_start': self.date_start, 'date_end': self.date_end}
        docids = self.env.context.get('active_ids', [])
        return self.env.ref('production_order_tracking.action_report_weekly_production_xlsx').report_action(docids, data=data)
