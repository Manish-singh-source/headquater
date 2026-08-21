# Dashboard Analytics Changes

## Overview

Updated the main dashboard (`/`) to make operational summaries clearer and more invoice-focused where needed.

## Sales Order Sections

- Added a new **Sales Order Counts** summary table above the detailed sales order table.
- Added a detailed **Sales Order Data** table with pagination.
- The detailed table now shows order-level quantities for:
  - PO quantity
  - allocation/update PO quantity
  - send to packaging quantity
  - packaged quantity
  - admin approval pending quantity
  - admin approved quantity
  - shipped quantity
  - invoice quantity
- Status columns now follow the order flow, so later-stage records still count in earlier milestone columns.

## Invoice Workflow Summary

Added a new table below **Purchase Analytics** showing invoice workflow counts:

- Total invoices
- Appointment date added
- POD file added
- GRN file added
- GRN number added
- DN details added
- Payment details added

## Dispatch Status

- Removed LR-related cards from **Dispatch Status**.
- Converted this section to invoice-level appointment counts.
- The section now displays:
  - Total appointments
  - Appointment date added
  - Appointment date pending

## Delivery Confirmation

- Converted **Delivery Confirmation** from sales-order-level counts to invoice-level counts.
- The section now uses total invoices as the POD baseline.
- It displays:
  - Total POD
  - POD received
  - POD not received

## GRN Status

- Converted **GRN Status** from sales-order-level counts to invoice-level counts.
- Added separate tracking for GRN file and GRN number.
- The section now displays:
  - Total GRN
  - GRN file done
  - GRN file not done
  - GRN number done
  - GRN number not done

## Payment Status

- Added invoice-level payment count cards while keeping the existing payment value cards.
- New count cards:
  - Total payment invoices
  - Payment details added
  - Payment details pending

## Files Changed

- `app/Http/Controllers/DashboardController.php`
- `resources/views/index.blade.php`
