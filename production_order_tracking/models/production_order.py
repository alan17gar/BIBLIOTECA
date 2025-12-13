from odoo import models, fields, api
from odoo.exceptions import UserError
from odoo.exceptions import ValidationError

class ProductionOrder(models.Model):
    _name = 'production.order'
    _description = 'Production Order'

    name = fields.Char(string='Order Number', required=True, copy=False, readonly=True, default=lambda self: ('New'))
    product_name = fields.Char(string='Product Name', required=True)
    quantity_kg = fields.Float(string='Total Quantity (kg)', required=True, digits='Product Unit of Measure')
    deadline_date = fields.Date(string='Deadline Date', required=True)
    produced_kg = fields.Float(string='Produced (kg)', compute='_compute_produced_kg', store=True, digits='Product Unit of Measure')
    remaining_kg = fields.Float(string='Remaining (kg)', compute='_compute_remaining_kg', store=True, digits='Product Unit of Measure')
    state = fields.Selection([
        ('draft', 'Draft'),
        ('in_progress', 'In Progress'),
        ('done', 'Done'),
        ('cancel', 'Cancelled'),
    ], string='Status', default='draft', tracking=True)
    daily_ids = fields.One2many('production.daily', 'order_id', string='Daily Production')
    is_overdue = fields.Boolean(string="Is Overdue", compute='_compute_is_overdue', store=True)
    daily_entry_count = fields.Integer(compute='_compute_daily_entry_count')

    @api.constrains('quantity_kg')
    def _check_quantity_kg(self):
        for order in self:
            if order.quantity_kg <= 0:
                raise ValidationError(_("The quantity must be greater than 0 kg."))

    def _compute_daily_entry_count(self):
        for order in self:
            order.daily_entry_count = len(order.daily_ids)

    def action_view_daily_entries(self):
        return {
            'name': ('Daily Production Entries'),
            'view_mode': 'tree,form',
            'res_model': 'production.daily',
            'type': 'ir.actions.act_window',
            'domain': [('order_id', '=', self.id)],
            'context': {'default_order_id': self.id}
        }

    @api.depends('deadline_date', 'remaining_kg', 'state')
    def _compute_is_overdue(self):
        today = fields.Date.today()
        for order in self:
            order.is_overdue = (
                order.deadline_date and
                order.deadline_date < today and
                order.remaining_kg > 0 and
                order.state == 'in_progress'
            )

    @api.depends('daily_ids.produced_today')
    def _compute_produced_kg(self):
        for order in self:
            order.produced_kg = sum(order.daily_ids.mapped('produced_today'))

    @api.depends('quantity_kg', 'produced_kg')
    def _compute_remaining_kg(self):
        for order in self:
            order.remaining_kg = max(0, order.quantity_kg - order.produced_kg)
            if order.remaining_kg == 0 and order.state == 'in_progress':
                order.state = 'done'

    @api.model
    def create(self, vals):
        if vals.get('name', ('New')) == ('New'):
            vals['name'] = self.env['ir.sequence'].next_by_code('production.order') or ('New')
        return super(ProductionOrder, self).create(vals)

    def action_confirm(self):
        self.write({'state': 'in_progress'})

    def action_cancel(self):
        self.write({'state': 'cancel'})

    def action_reset_to_draft(self):
        self.write({'state': 'draft'})
