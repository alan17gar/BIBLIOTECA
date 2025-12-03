from odoo import models, fields, api
from odoo.exceptions import ValidationError

class ProductionDaily(models.Model):
    _name = 'production.daily'
    _description = 'Daily Production'

    order_id = fields.Many2one('production.order', string='Production Order', required=True, ondelete='cascade')
    date = fields.Date(string='Date', required=True, default=fields.Date.today)
    produced_today = fields.Float(string='Produced Today (kg)', required=True, digits='Product Unit of Measure')
    lot_id = fields.Many2one('production.lot', string='Lot', required=True)
    note = fields.Text(string='Note')

    @api.constrains('produced_today')
    def _check_produced_today(self):
        for record in self:
            if record.produced_today <= 0:
                raise models.ValidationError("The produced quantity must be greater than zero.")

    @api.constrains('produced_today')
    def _check_overproduction(self):
        for record in self:
            if not record.order_id:
                continue

            # `record._origin.id` is False for a new record.
            # This logic correctly calculates the prospective total for both new and edited records.
            other_lines = record.order_id.daily_ids.filtered(lambda l: l.id != record._origin.id)
            new_total = sum(other_lines.mapped('produced_today')) + record.produced_today

            if new_total > record.order_id.quantity_kg:
                raise ValidationError(
                    f'The produced quantity ({new_total} kg) cannot exceed the total committed quantity ({record.order_id.quantity_kg} kg).'
                )

    _sql_constraints = [
        ('unique_daily_production', 'unique(order_id, date, lot_id)', 'A production record for this order, date, and lot already exists.')
    ]
