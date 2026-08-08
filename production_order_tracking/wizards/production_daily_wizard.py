from odoo import models, fields, api

class ProductionDailyWizard(models.TransientModel):
    _name = 'production.daily.wizard'
    _description = 'Wizard to create a daily production record'

    order_id = fields.Many2one('production.order', string='Production Order', readonly=True)
    date = fields.Date(string='Date', default=fields.Date.today, required=True)
    produced_today = fields.Float(string='Produced Today (kg)', required=True)
    lot_id = fields.Many2one('production.lot', string='Lot')
    new_lot = fields.Boolean(string='Create New Lot')

    @api.onchange('new_lot')
    def _onchange_new_lot(self):
        if self.new_lot:
            self.lot_id = False

    def create_daily_production(self):
        self.ensure_one()
        lot = self.lot_id
        if self.new_lot:
            lot = self.env['production.lot'].create({})

        self.env['production.daily'].create({
            'order_id': self.order_id.id,
            'date': self.date,
            'produced_today': self.produced_today,
            'lot_id': lot.id,
        })
        return {'type': 'ir.actions.act_window_close'}
