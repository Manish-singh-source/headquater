# Testing List

## Access control

- [ ] Ensure only authorized personnel can view, edit, delete, and download any resource.[1]

## Staff management

- [ ] Implement staff list with “view all staff members”.[1]
- [ ] Implement detailed staff view including related permissions.[1]
- [ ] Implement edit staff details.[1]
- [ ] Implement delete staff (use soft delete if audit is required).[1]
- [ ] Implement status update for single staff.[1]
- [ ] Implement multi-delete for staff.[1]
- [ ] Implement multi-status update for staff.[1]
- [ ] Implement add new staff flow.[1]
- [ ] Implement Excel download for staff list.[1]
- [ ] Validate role and warehouse as required fields when creating staff.[1]
- [ ] Enforce unique, valid email for staff.[1]
- [ ] Enforce secure password rules (length, complexity).[1]
- [ ] Validate phone number format for staff.[1]

## Role management

- [ ] Implement roles CRUD (create, list, update, delete).[1]
- [ ] Map permissions to roles and integrate with access control.[1]

## Warehouse management

- [ ] Implement warehouse list with detail view.[1]
- [ ] Implement edit warehouse details.[1]
- [ ] Implement delete warehouse using soft delete.[1]
- [ ] Implement single warehouse status update.[1]
- [ ] Implement multi-delete for warehouses (soft delete).[1]
- [ ] Implement multi-status update for warehouses (exclude default).[1]
- [ ] Implement add new warehouse flow.[1]
- [ ] Implement Excel download for warehouses.[1]
- [ ] Configure one default warehouse that cannot be deleted or deactivated.[1]
- [ ] Enforce unique warehouse name on add/edit.[1]

## SKU mapping

- [ ] Implement list of all SKU mappings.[1]
- [ ] Implement edit SKU mapping.[1]
- [ ] Implement delete SKU mapping.[1]

## Vendor management

- [ ] Implement add new vendor flow.[1]
- [ ] Implement vendor list with detail view.[1]
- [ ] Implement edit vendor details.[1]
- [ ] Implement delete vendor using soft delete.[1]
- [ ] Implement vendor status update.[1]
- [ ] Implement multi-delete for vendors.[1]
- [ ] Implement multi-status update for vendors.[1]
- [ ] Implement Excel download for vendors.[1]
- [ ] Enforce unique vendor code on add/edit.[1]

## Product management

- [ ] Implement bulk add products (file or UI).[1]
- [ ] Implement product list view.[1]
- [ ] Implement edit product details.[1]
- [ ] Implement delete product using soft delete.[1]
- [ ] Implement bulk update of products.[1]
- [ ] Implement multi-delete for products.[1]
- [ ] Implement Excel export for products.[1]
- [ ] Enforce unique product SKU on add/edit.[1]

## Customer and group management

- [ ] Implement bulk add customer groups.[1]
- [ ] Implement customer group list with detail view.[1]
- [ ] Implement view of customers under each customer group.[1]
- [ ] Implement customer detail view inside group.[1]
- [ ] Implement edit customer details inside group.[1]
- [ ] Implement delete customer inside group.[1]
- [ ] Implement status update for customers inside group.[1]
- [ ] Implement multi-delete for customers inside group.[1]
- [ ] Implement multi-status update for customers inside group.[1]
- [ ] Implement add single customer to a group.[1]
- [ ] Implement bulk add customers to a group.[1]
- [ ] Implement edit customer group details.[1]
- [ ] Implement delete customer group using soft delete.[1]
- [ ] Implement status update for customer groups.[1]
- [ ] Implement multi-delete for customer groups.[1]
- [ ] Implement multi-status update for customer groups.[1]
- [ ] Enforce unique customer group name on add/edit.[1]
- [ ] Show confirmation popup before deleting customer group.[1]
- [ ] Block deletion if customers are associated and show message.[1]

- [ ] Implement add new customer flow.[1]
- [ ] Implement customer list with detail view.[1]
- [ ] Implement edit customer details.[1]
- [ ] Implement delete customer using soft delete.[1]
- [ ] Implement customer status update.[1]
- [ ] Implement multi-delete for customers.[1]
- [ ] Implement multi-status update for customers.[1]
- [ ] Implement Excel download for customers.[1]
- [ ] Enforce unique customer email on add/edit.[1]

## Sales orders

### Check availability

- [ ] Implement “Check Availability” pre-step for sales orders.[1]
- [ ] Show only active warehouses in dropdown.[1]
- [ ] Show only active customer groups in dropdown.[1]
- [ ] Implement Excel upload with columns: Product SKU, Quantity, Block Quantity (for SO).[1]
- [ ] Validate all required columns in uploaded file.[1]
- [ ] Validate customer existence (facility name) in file vs system.[1]
- [ ] Validate all product SKUs in file vs system.[1]
- [ ] Check requested quantity vs stock per SKU.[1]
- [ ] Generate Excel report: Product SKU, Requested Qty, Available Qty, Status.[1]

### Create sales order

- [ ] Reuse all validations from “Check Availability”.[1]
- [ ] Validate vendor existence.[1]
- [ ] Implement sales order creation flow.[1]
- [ ] Auto-generate purchase orders for out-of-stock products.[1]

### Sales order list and details

- [ ] Implement SO list with tabs: All, Blocked, Completed, Ready to Package, Ready to Ship, Shipped, Delivered, Cancelled.[1]
- [ ] Implement delete sales order.[1]
- [ ] Implement SO details view with tabs: all, packaging, packaged, shipped, delivered, cancelled products.[1]
- [ ] Implement sales order products list.[1]
- [ ] Implement delete selected sales order product.[1]
- [ ] Implement Excel download of sales order products.[1]
- [ ] Implement update of SO products from Excel template.[1]
- [ ] Implement sales order status update.[1]
- [ ] Implement invoice generation for sales order.[1]

## Purchase orders

### Generation rules

- [ ] When generating from SO, group products by vendor.[1]
- [ ] Create a separate purchase order per vendor.[1]

### List and details

- [ ] Implement PO list with tabs: All, Pending, Received, Cancelled.[1]
- [ ] Implement manual create purchase order.[1]
- [ ] Implement delete purchase order.[1]
- [ ] Implement multi-delete for purchase orders.[1]

- [ ] Implement PO details with tabs: all products, received products, pending products.[1]
- [ ] Implement purchase order products list.[1]
- [ ] Implement delete PO product.[1]
- [ ] Implement multi-delete for PO products.[1]
- [ ] Implement Excel download for PO products.[1]
- [ ] Implement update PO products via Excel template.[1]
- [ ] Implement inline update for PO product.[1]

### Vendor PI, GRN, invoice, payment

- [ ] Implement add vendor PI with products list.[1]
- [ ] Implement add/update/delete vendor PI product.[1]
- [ ] Implement add vendor GRN with view, update, delete.[1]
- [ ] Implement add vendor invoice with view, update, delete.[1]
- [ ] Implement add vendor payment with view, update, delete.[1]
- [ ] Implement purchase order status change workflow.[1]

### Approvals

- [ ] Implement approve received purchase order and update warehouse stock.[1]
- [ ] Implement reject received purchase order without stock change.[1]

## Received orders

- [ ] Implement tabs: All, Pending, Completed, Sent for Approval, Approved, Rejected.[1]
- [ ] Implement received orders list.[1]
- [ ] Implement view of received order products.[1]
- [ ] Implement Excel export of received order products.[1]
- [ ] Implement update from received order Excel template.[1]
- [ ] Implement “send for approval” action.[1]
- [ ] After sending for approval, hide update and submit actions.[1]

## Packaging orders

- [ ] Implement tabs: All, Pending Packaged, Packaged, Ready to Ship, Cancelled.[1]
- [ ] Implement pagination for packaging orders list.[1]
- [ ] Implement packaging orders list view.[1]

### Warehouse side

- [ ] Implement view packaging order products.[1]
- [ ] Implement Excel export for packaging order products.[1]
- [ ] Implement update via Excel (final dispatch qty, box count, weight only).[1]
- [ ] Implement “mark ready to ship” request (warehouse only).[1]

### Admin side

- [ ] Implement approve ready-to-ship request.[1]
- [ ] Implement filter products by warehouse.[1]
- [ ] Implement Excel export for admin packaging view.[1]
- [ ] On approval, update order status to Ready to Ship.[1]

## Ready to ship, shipped, delivered

- [ ] Implement Ready to Ship list with tabs: All, Pending Ready to Ship, Shipped, Delivered, Cancelled.[1]
- [ ] Implement pagination for Ready to Ship list.[1]
- [ ] Implement Ready to Ship orders list view.[1]

- [ ] Implement Ready to Ship detail view with tabs: all, ready to ship, shipped, delivered, cancelled customer product status.[1]
- [ ] Implement list of customers with product quantities.[1]
- [ ] Implement status change to Shipped.[1]
- [ ] Implement status change to Delivered.[1]
- [ ] Implement status change to Cancelled.[1]

## Product issues and returns

- [ ] Implement vendor order issues list (shortage, damaged, wrong item, etc.).[1]
- [ ] Implement warehouse/customer order issues list.[1]

- [ ] Implement vendor return orders list.[1]
- [ ] Implement vendor return status: pending, accept, return to vendor.[1]
- [ ] On accept, increase warehouse stock.[1]
- [ ] On return to vendor, do not change stock.[1]

- [ ] Implement customer return orders list.[1]
- [ ] Implement customer return status: pending, approved, rejected.[1]
- [ ] On approval, increase warehouse stock.[1]
- [ ] On rejection, do not change stock.[1]

## Tracking and reports

- [ ] Implement track sales order by order number.[1]
- [ ] Implement track purchase order by order number.[1]

- [ ] Implement sales reports.[1]
- [ ] Implement inventory reports.[1]
- [ ] Implement purchase reports.[1]

## Excel formats

- [ ] Define/document Staff Excel format.[2]
- [ ] Define/document Warehouse Excel format.[2]
- [ ] Define/document SKU Mapping Excel format.[2]
- [ ] Define/document Vendor Excel format.[2]
- [ ] Define/document Product Excel format.[2]
- [ ] Define/document Customer Group Excel format.[2]
- [ ] Define/document Customer Excel format.[2]
- [ ] Define/document Sales Order Check Availability Excel format.[2]
- [ ] Define/document Sales Order Create Excel format.[2]
- [ ] Define/document Purchase Order Create Excel format.[2]
- [ ] Define/document Received Order Excel format.[2]
- [ ] Define/document Packaging Order Excel format.[2]
- [ ] Define/document Ready to Ship Order Excel format.[2]
- [ ] Define/document Product Issue Excel format.[2]
- [ ] Define/document Return Order Excel format.[2]
