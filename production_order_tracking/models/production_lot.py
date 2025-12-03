from odoo import models, fields, api

class ProductionLot(models.Model):
    _name = 'production.lot'
    _description = 'Production Lot'

    name = fields.Char(string='Lot Number', required=True, copy=False, readonly=True, default=lambda self: ('New'))
    start_date = fields.Date(string='Start Date')
    end_date = fields.Date(string='End Date')
    daily_ids = fields.One2many('production.daily', 'lot_id', string='Daily Productions', readonly=True)
    total_kg = fields.Float(string='Total Produced (kg)', compute='_compute_total_kg', store=True, digits='Product Unit of Measure')
    order_ids = fields.Many2many('production.order', string='Production Orders', compute='_compute_order_ids', store=True)

    @api.depends('daily_ids.produced_today')
    def _compute_total_kg(self):
        for lot in self:
            lot.total_kg = sum(lot.daily_ids.mapped('produced_today'))

    @api.depends('daily_ids.order_id')
    def _compute_order_ids(self):
        for lot in self:
            lot.order_ids = [(6, 0, lot.daily_ids.mapped('order_id').ids)]

    @api.model
    def create(self, vals):
        if vals.get('name', ('New')) == ('New'):
            vals['name'] = self.env['ir.sequence'].next_by_code('production.lot') or ('New')
        return super(ProductionLot, self).create(vals)
