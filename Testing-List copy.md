# Testing List

## Access control

- [ ] Ensure only authorized personnel can view, edit, delete, and download any resource.[1]


## Role management

- [x] Implement roles CRUD (create, list, update, delete).
- [x] Map permissions to roles and integrate with access control.

## Staff management

- [x] List Staffs 
- [x] Create Staff
- [x] Edit Staff
- [x] Delete Staff
- [x] Multi Delete Staff
- [x] Toggle Status Staff
- [ ] Multi Toggle Status Staff
- [x] View Staff Staff


## Warehouse management

- [x] List Warehouses
- [x] Add Warehouses
- [x] Edit Warehouses
- [x] Delete Warehouses
- [x] Multi Delete Warehouses
- [x] Toggle Status Warehouses
- [x] Multi Toggle Status Warehouses
- [x] View Warehouses
- [x] Tab Switching Warehouses


## Vendor management

- [x] List Vendors
- [x] Add Vendors
- [x] Edit Vendors
- [x] Delete Vendors
- [x] Multi Delete Vendors
- [x] Toggle Status Vendors
- [x] Multi Toggle Status Vendors
- [x] View Vendors
- [x] Tab Switching Vendors


## Customer

- [x] List Customer
- [x] Add Customer
- [x] Edit Customer
- [x] Delete Customer
- [x] Multi Delete Customer
- [x] Toggle Status Customer
- [x] Multi Toggle Status Customer
- [ ] View Customer (after any order)
- [x] Tab Switching Customer

## Customer group management

- [x] List Customer Groups 
- [x] Add Customers via excel in group
- [x] Edit Customer group name only 
- [x] Delete Customer group 
- [x] Multi Delete Customer group
- [x] Toggle Status Customer group
- [x] Multi Toggle Status Customer group
- [x] Tab Switching Customer group
- [ ] View Customer group
    - [ ] List Customer
    - [ ] Add Customer single
    - [ ] Add Customer in bulk
    - [ ] Edit Customer
    - [ ] Export Customer
    - [ ] Edit Customer in bulk
    - [ ] Delete Customer
    - [ ] Multi Delete Customer
    - [ ] Toggle Status Customer
    - [ ] Multi Toggle Status Customer
    - [ ] View Customer
    - [ ] Tab Switching Customer    


## Product management

- [x] Add products in bulk 
- [x] List products 
- [x] Export products File
- [x] Update product  - check original quantity, block and available other data will update as it is 
- [x] Update products in bulk via excel  - check original quantity, block and available other data will update as it is 
- [x] Delete Proudct - delete sku mapping product also 
- [x] Multi Delete Proudct - delete sku mapping product also 


## SKU mapping

- [x] Add sku mapping products in bulk 
- [x] List sku mapping products
- [x] Export sku mapping products File
- [x] Update sku mapping product 
- [x] Update sku mapping products in bulk via excel
- [x] Delete Proudct
- [ ] Multi Delete Proudct


## Sales orders

### Check availability

- [x] Active Customer Groups dropdown 
- [x] Active warehouses dropdown and all warehouse option
- [x] Excel file upload for check availibility 
- [x] If any column is not present throw error 
- [x] If any column is blank throw error
- [x] Proper available quantity 
- [x] Proper unavailable quantity 
- [x] Proper purchase order quantity (same as unavailable quantity)
- [x] Proper warehouse allocation (if all warehouse selected)


### Create sales order
- [ ] Sales Order Create 
- [ ] Create purchase order using purchase order quantity or auto generated 
- [ ] 


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








## Soft Delete 

1. Vendor 
2. Product 
3. SKU Mapping 
4. Customer Group 
5. Customer 

