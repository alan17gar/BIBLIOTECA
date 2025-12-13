from odoo import models, api

class WeeklyReport(models.AbstractModel):
    _name = 'report.production_order_tracking.weekly_report_template'
    _description = 'Weekly Production Report'

    @api.model
    def _get_report_values(self, docids, data=None):
        date_start = data['data']['date_start']
        date_end = data['data']['date_end']

        domain = [
            ('daily_ids.date', '>=', date_start),
            ('daily_ids.date', '<=', date_end),
        ]

        if docids:
            domain.append(('id', 'in', docids))

        orders = self.env['production.order'].search(domain)

        return {
            'doc_ids': docids,
            'doc_model': 'production.order',
            'docs': orders,
            'data': data,
            'get_weekly_production': self._get_weekly_production,
        }

    def _get_weekly_production(self, order, data):
        date_start = data['data']['date_start']
        date_end = data['data']['date_end']
        return sum(
            order.daily_ids.filtered(
                lambda d: date_start <= d.date <= date_end
            ).mapped('produced_today')
        )
