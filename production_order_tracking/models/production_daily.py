from odoo import models, fields, api, _
from odoo.exceptions import ValidationError

class ProductionDaily(models.Model):
    _name = 'production.daily'
    _description = 'Daily Production Entry'

    order_id = fields.Many2one('production.order', string='Production Order', required=True, ondelete='cascade')
    date = fields.Date(string='Date', required=True, default=fields.Date.context_today)
    produced_today = fields.Float(string='Produced (kg)', required=True)
    lot_id = fields.Many2one('production.lot', string='Lot', required=True)
    note = fields.Text(string='Note')

    @api.constrains('produced_today')
    def _check_produced_today_positive(self):
        for record in self:
            if record.produced_today <= 0:
                raise ValidationError(_("Produced quantity must be greater than 0."))

    @api.constrains('produced_today', 'order_id')
    def _check_overproduction(self):
        for record in self:
            if record.order_id:
                # This check is now more robust as it considers the total produced
                total_produced = record.order_id.produced_kg
                if total_produced > record.order_id.quantity_kg:
                    raise ValidationError(_(
                        "The total produced quantity (%.2f kg) cannot exceed the ordered quantity (%.2f kg).",
                        total_produced,
                        record.order_id.quantity_kg
                    ))
